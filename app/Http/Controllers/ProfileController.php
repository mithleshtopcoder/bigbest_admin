<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    public function blank(Request $request): View
    {
        return view('blank');
    }

    /**
     * AJAX endpoint for fetching bills/orders with pagination
     */
    public function getBills(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');

        // Build query
        $query = \App\Models\Order::with(['customer', 'store', 'createdBy']);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('store', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Get total count before pagination
        $totalRecords = \App\Models\Order::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $orders = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        // Transform orders to match the expected format
        $data = $orders->map(function ($order) {
            // Get user name (check for name field or use email as fallback)
            $heldBy = 'N/A';
            if ($order->createdBy) {
                $heldBy = $order->createdBy->name ?? $order->createdBy->email ?? 'N/A';
            }

            return [
                'id' => str_pad($order->id, 3, '0', STR_PAD_LEFT),
                'date' => $order->created_at->format('Y-m-d'),
                'time' => $order->created_at->format('h:i A'),
                'store' => $order->store ? $order->store->name : 'N/A',
                'customer' => $order->customer ? $order->customer->full_name : 'N/A',
                'items' => $order->total_items ?? 0,
                'amount' => number_format($order->total_amount ?? 0, 0, '.', ','),
                'held_by' => $heldBy,
                'order_id' => $order->id, // Keep original ID for routes
            ];
        });

        // If no orders, provide dummy data for testing
        if ($data->isEmpty() && empty($search)) {
            $dummyData = [
                [
                    'id' => '001',
                    'date' => '2024-01-15',
                    'time' => '10:30 AM',
                    'store' => 'Main Store',
                    'customer' => 'Rajesh Kumar',
                    'items' => 3,
                    'amount' => '45,680',
                    'held_by' => 'John Doe',
                    'order_id' => 1,
                ],
                [
                    'id' => '002',
                    'date' => '2024-01-15',
                    'time' => '11:15 AM',
                    'store' => 'Branch Store',
                    'customer' => 'Priya Sharma',
                    'items' => 2,
                    'amount' => '32,450',
                    'held_by' => 'Jane Smith',
                    'order_id' => 2,
                ],
                [
                    'id' => '003',
                    'date' => '2024-01-15',
                    'time' => '12:45 PM',
                    'store' => 'Main Store',
                    'customer' => 'Amit Patel',
                    'items' => 4,
                    'amount' => '67,800',
                    'held_by' => 'John Doe',
                    'order_id' => 3,
                ],
                [
                    'id' => '004',
                    'date' => '2024-01-15',
                    'time' => '02:20 PM',
                    'store' => 'Branch Store',
                    'customer' => 'Sneha Reddy',
                    'items' => 1,
                    'amount' => '28,900',
                    'held_by' => 'Jane Smith',
                    'order_id' => 4,
                ],
                [
                    'id' => '005',
                    'date' => '2024-01-15',
                    'time' => '03:10 PM',
                    'store' => 'Main Store',
                    'customer' => 'Vikram Singh',
                    'items' => 5,
                    'amount' => '89,500',
                    'held_by' => 'John Doe',
                    'order_id' => 5,
                ],
                [
                    'id' => '006',
                    'date' => '2024-01-15',
                    'time' => '04:30 PM',
                    'store' => 'Branch Store',
                    'customer' => 'Meera Joshi',
                    'items' => 2,
                    'amount' => '34,600',
                    'held_by' => 'Jane Smith',
                    'order_id' => 6,
                ],
                [
                    'id' => '007',
                    'date' => '2024-01-15',
                    'time' => '05:15 PM',
                    'store' => 'Main Store',
                    'customer' => 'Rahul Verma',
                    'items' => 3,
                    'amount' => '56,750',
                    'held_by' => 'John Doe',
                    'order_id' => 7,
                ],
                [
                    'id' => '008',
                    'date' => '2024-01-15',
                    'time' => '06:00 PM',
                    'store' => 'Branch Store',
                    'customer' => 'Anjali Desai',
                    'items' => 4,
                    'amount' => '72,300',
                    'held_by' => 'Jane Smith',
                    'order_id' => 8,
                ],
            ];
            
            // Apply pagination to dummy data
            $dummyData = array_slice($dummyData, $start, $length);
            $data = collect($dummyData);
            $filteredRecords = count($dummyData);
            $totalRecords = 8;
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
