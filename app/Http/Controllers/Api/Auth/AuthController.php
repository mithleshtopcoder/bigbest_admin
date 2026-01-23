<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\CustomerAddress;
use App\Services\SmsOtpService;
use App\Jobs\SendSmsJob;


class AuthController extends Controller
{
    /**
     * Register a new customer
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'required|string|max:20|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'status' => 'active',
        ]);

        $token = $customer->createToken('customer-token')->plainTextToken;

         SendSmsJob::dispatch(
    'welcome_customer',        // template key
    $customer->phone,          // customer phone
    [
        'NAME' => $customer->first_name
    ]
    );

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'customer' => $customer->makeHidden(['password', 'remember_token']),
                'token' => $token,
            ]
        ], 201);
    }

    /**
     * Login customer
     */
      public function login(Request $request, SmsOtpService $otpService)
{
    // Validate inputs
    $validator = Validator::make($request->all(), [
        'email' => 'required_without:phone|email',
        'phone' => 'required_without:email|string',
        'password' => 'nullable|string', // optional for OTP login
        'otp' => 'nullable|string|size:6', // optional for OTP login
        'flow' => 'nullable|in:login,signup', // default OTP flow
        'device_token' => 'nullable|string',
        'device_id' => 'nullable|string',
        'fcm_token' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $phone = $request->phone;
    $email = $request->email;

    // -----------------------------
    // 1️⃣ OTP LOGIN
    // -----------------------------
    if ($phone && $request->has('otp')) {
        try {
            $data = $otpService->verifyOtp($phone, $request->otp, $request->flow ?? 'login');

            // Update device info
            if (isset($data['user'])) {
                $data['user']->update($request->only(['device_token', 'device_id', 'fcm_token']));
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    // -----------------------------
    // 2️⃣ Auto-send OTP if phone is provided but no password or OTP
    // -----------------------------
    if ($phone && !$request->password) {
        try {
            $data = $otpService->sendOtp($phone, $request->flow ?? 'login');

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    // -----------------------------
    // 3️⃣ PASSWORD LOGIN
    // -----------------------------
    if ($password = $request->password) {
        $customer = Customer::where('email', $email)
            ->orWhere('phone', $phone)
            ->first();

        if (!$customer || !\Hash::check($password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($customer->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is ' . $customer->status
            ], 403);
        }

        // Update device info
        $customer->update($request->only(['device_token', 'device_id', 'fcm_token']));

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'customer' => $customer->makeHidden(['password', 'remember_token']),
                'token' => $token,
            ]
        ], 200);
    }

    // -----------------------------
    // 4️⃣ If none of the above, invalid request
    // -----------------------------
    return response()->json([
        'success' => false,
        'message' => 'Provide valid login credentials (password or OTP).'
    ], 422);
}

    /**
     * Get customer profile
     */
    public function profile(Request $request)
    {
        $customer = $request->user();

        return response()->json([
            'success' => true,
            'data' => $customer->makeHidden(['password', 'remember_token'])
        ], 200);
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'sometimes|required|string|max:20|unique:customers,phone,' . $customer->id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer->update($request->only([
            'first_name', 'last_name', 'email', 'phone',
            'date_of_birth', 'gender', 'address', 'city',
            'state', 'pincode', 'country'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $customer->makeHidden(['password', 'remember_token'])
        ], 200);
    }

    /**
     * Update customer photo
     */
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        // Delete old photo if exists
        if ($customer->photo && Storage::disk('public')->exists($customer->photo)) {
            Storage::disk('public')->delete($customer->photo);
        }

        // Upload new photo
        $photoPath = $request->file('photo')->store('customers/photos', 'public');
        $customer->update(['photo' => $photoPath]);

        return response()->json([
            'success' => true,
            'message' => 'Photo updated successfully',
            'data' => [
                'photo' => asset('storage/' . $photoPath),
                'customer' => $customer->makeHidden(['password', 'remember_token'])
            ]
        ], 200);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        if (!Hash::check($request->current_password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $customer->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ], 200);
    }

    /**
     * Forgot password
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Generate reset token (you can implement OTP or email token here)
        // For now, returning success message
        return response()->json([
            'success' => true,
            'message' => 'Password reset link/OTP sent successfully'
        ], 200);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|string',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Verify token here (implement OTP or token verification)
        // For now, directly updating password
        $customer->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ], 200);
    }

    /**
     * Logout customer
     */
    public function logout(Request $request)
    {
        $customer = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        // Optionally revoke all tokens
        // $customer->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Verify email
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Verify OTP here (implement OTP verification logic)
        // For now, directly marking as verified
        $customer->update(['email_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ], 200);
    }

    /**
     * Verify phone
     */
    public function verifyPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Verify OTP here (implement OTP verification logic)
        // For now, directly marking as verified
        $customer->update(['phone_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully'
        ], 200);
    }

    /**
     * Resend verification code
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|string',
            'type' => 'required|in:email,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Send verification code here (implement OTP sending logic)

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully'
        ], 200);
    }

    /**
     * Get customer addresses
     */
   public function addresses(Request $request)
    {
        $customer = $request->user();

        $addresses = CustomerAddress::where('customer_id', $customer->id)
                        ->orderByDesc('is_default') // default first
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ], 200);
    }

    // Add new address
    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:home,work,other',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:15',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'country' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        if ($request->is_default) {
            CustomerAddress::where('customer_id', $customer->id)
                ->update(['is_default' => false]);
        }

        $address = CustomerAddress::create([
            'customer_id' => $customer->id,
            'type' => $request->type,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => $request->country,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Address added successfully',
            'data' => $address
        ], 201);
    }

    // Update existing address
    public function updateAddress(Request $request, $id)
    {
        $address = CustomerAddress::find($id);

        if (!$address || $address->customer_id != $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|required|in:home,work,other',
            'contact_name' => 'sometimes|required|string|max:255',
            'contact_phone' => 'sometimes|required|string|max:15',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'pincode' => 'sometimes|required|string|max:10',
            'country' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->is_default) {
            CustomerAddress::where('customer_id', $request->user()->id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => $address
        ], 200);
    }

    // Delete address
    public function deleteAddress(Request $request, $id)
    {
        $address = CustomerAddress::find($id);

        if (!$address || $address->customer_id != $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully'
        ], 200);
    }
    /**
     * Get customer wallet
     */
    public function wallet(Request $request)
    {
        $customer = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $customer->wallet_balance,
                'currency' => 'INR',
                'transactions' => [] // Would fetch from transactions table
            ]
        ], 200);
    }

    /**
     * Get customer loyalty points
     */
    public function loyaltyPoints(Request $request)
    {
        $customer = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'points' => $customer->loyalty_points,
                'history' => [] // Would fetch from loyalty_points_history table
            ]
        ], 200);
    }

    /**
     * Get customer orders
     */
    public function orders(Request $request)
    {
        // This would typically fetch from orders table
        // For now, returning empty array

        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }

    /**
     * Get customer order details
     */
    public function orderDetails(Request $request, $id)
    {
        // This would typically fetch from orders table
        // For now, returning empty object

        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }
}