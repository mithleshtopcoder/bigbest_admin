<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreServiceArea;
use App\Models\StoreUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GeocodingService;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    public function index()
    {
        return view('store-management.manage-store.index');
    }

    /**
     * AJAX endpoint for fetching stores with pagination (DataTables)
     */
    public function getStores(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        // Build query
        $query = Store::query();

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('manager_name', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Get total count before pagination
        $totalRecords = Store::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $stores = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $stores->map(function ($store) {
            $statusBadge = match($store->status) {
                'active' => '<span class="badge bg-success">Active</span>',
                'inactive' => '<span class="badge bg-danger">Inactive</span>',
                'maintenance' => '<span class="badge bg-warning">Maintenance</span>',
                default => '<span class="badge bg-secondary">' . ucfirst($store->status) . '</span>'
            };

            return [
                'id' => $store->id,
                'code' => $store->code ?? 'N/A',
                'name' => $store->name ?? 'N/A',
                'address' => $store->address ? (strlen($store->address) > 50 ? substr($store->address, 0, 50) . '...' : $store->address) : 'N/A',
                'city' => $store->city ?? 'N/A',
                'phone' => $store->phone ?? 'N/A',
                'manager_name' => $store->manager_name ?? 'N/A',
                'status' => $statusBadge,
                'status_value' => $store->status,
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }
    
    public function create()
    {
        return view('store-management.manage-store.create');
    }
    
    public function edit($id)
    {
        $store = Store::with('users')->findOrFail($id);
        $assignedUsers = $store->users;
        // Get all users for the dropdown (we'll filter assigned ones in JavaScript)
        $allUsers = User::orderBy('name')->get();
        return view('store-management.manage-store.edit', compact('store', 'assignedUsers', 'allUsers'));
    }

   public function store(Request $request)
{
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255|unique:stores,code',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'pincode' => 'nullable|string|max:10',
        'country' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'manager_name' => 'nullable|string|max:255',
        'manager_phone' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive,maintenance',
        'is_online' => 'nullable|boolean',
        'opening_time' => 'nullable|date_format:H:i',
        'closing_time' => 'nullable|date_format:H:i',
    ]);

    try {
        DB::beginTransaction();

        $validated['is_online'] = $request->has('is_online');

        // 🔹 Auto-generate latitude & longitude (with CACHE)
        if (empty($validated['latitude']) && empty($validated['longitude'])) {

            $fullAddress = collect([
                $validated['address'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['pincode'] ?? null,
                $validated['country'] ?? null,
            ])->filter()->map(fn($v) => trim($v))->implode(', ');
// dd($fullAddress);
            if ($fullAddress) {

                $cacheKey = 'geo_' . md5($fullAddress);

                $coords = Cache::remember(
                    $cacheKey,
                    now()->addDays(30),
                    function () use ($fullAddress) {
                        return GeocodingService::getLatLng($fullAddress);
                    }
                );
// dd($coords);
                if ($coords) {
                    $validated['latitude']  = $coords['latitude'];
                    $validated['longitude'] = $coords['longitude'];
                }
            }
        }

        Store::create($validated);

        DB::commit();

        return redirect()
            ->route('store-management.manage-store')
            ->with('success', 'Store created successfully.');
    } catch (\Exception $e) {
        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Failed to create store: ' . $e->getMessage());
    }
}

    public function update(Request $request, $id)
{
    $store = Store::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255|unique:stores,code,' . $id,
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'pincode' => 'nullable|string|max:10',
        'country' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'manager_name' => 'nullable|string|max:255',
        'manager_phone' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive,maintenance',
        'is_online' => 'nullable|boolean',
        'opening_time' => 'nullable|date_format:H:i',
        'closing_time' => 'nullable|date_format:H:i',
    ]);

    try {
        DB::beginTransaction();

        // Convert is_online checkbox to boolean
        $validated['is_online'] = $request->has('is_online');

        // Build the full old and new addresses
        $existingAddress = collect([
            $store->address,
            $store->city,
            $store->state,
            $store->pincode,
            $store->country,
        ])->filter()->implode(', ');

        $newAddress = collect([
            $validated['address'] ?? null,
            $validated['city'] ?? null,
            $validated['state'] ?? null,
            $validated['pincode'] ?? null,
            $validated['country'] ?? null,
        ])->filter()->map(fn($v) => trim($v))->implode(', ');

        // If address changed, update latitude & longitude
        if ($newAddress && $newAddress !== $existingAddress) {
            $cacheKey = 'geo_' . md5($newAddress);

            $coords = Cache::remember(
                $cacheKey,
                now()->addDays(30),
                function () use ($newAddress) {
                    return \App\Services\GeocodingService::getLatLng($newAddress);
                }
            );

            if ($coords) {
                $validated['latitude'] = $coords['latitude'];
                $validated['longitude'] = $coords['longitude'];
            } else {
                // Optional: set to null if geocoding fails
                $validated['latitude'] = null;
                $validated['longitude'] = null;
            }
        }

        $store->update($validated);

        DB::commit();

        return redirect()->route('store-management.manage-store')
            ->with('success', 'Store updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
            ->with('error', 'Failed to update store: ' . $e->getMessage());
    }
}

    /**
     * Assign users to store
     */
    public function assignUsers(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Get currently assigned user IDs
            $currentUserIds = $store->users()->pluck('users.id')->toArray();
            
            // Users to add (new selections)
            $usersToAdd = array_diff($validated['user_ids'], $currentUserIds);
            
            // Users to remove (deselected)
            $usersToRemove = array_diff($currentUserIds, $validated['user_ids']);
            
            // Add new users using StoreUser model
            foreach ($usersToAdd as $userId) {
                StoreUser::firstOrCreate([
                    'store_id' => $store->id,
                    'user_id' => $userId,
                ]);
            }
            
            // Remove unselected users
            if (!empty($usersToRemove)) {
                StoreUser::where('store_id', $store->id)
                    ->whereIn('user_id', $usersToRemove)
                    ->delete();
            }
            
            DB::commit();
            
            return redirect()->route('store-management.manage-store.edit', $id)
                ->with('success', 'Users assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to assign users: ' . $e->getMessage());
        }
    }

    /**
     * Add a single user to store (AJAX)
     */
    public function addUser(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            // Check if user is already assigned
            $exists = StoreUser::where('store_id', $store->id)
                ->where('user_id', $validated['user_id'])
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already assigned to this store.'
                ], 400);
            }
            
            // Add user using StoreUser model
            StoreUser::create([
                'store_id' => $store->id,
                'user_id' => $validated['user_id'],
            ]);
            
            // Get the user details for response
            $user = User::find($validated['user_id']);
            
            return response()->json([
                'success' => true,
                'message' => 'User assigned successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number ?? '-',
                    'status' => $user->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove user from store
     */
    public function removeUser($storeId, $userId)
    {
        try {
            $store = Store::findOrFail($storeId);
            $user = User::findOrFail($userId);
            
            // Remove using StoreUser model
            StoreUser::where('store_id', $storeId)
                ->where('user_id', $userId)
                ->delete();
            
            return response()->json(['success' => true, 'message' => 'User removed from store successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);
            $store->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Store deleted successfully.'
                ]);
            }

            return redirect()->route('store-management.manage-store')
                ->with('success', 'Store deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete store: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('store-management.manage-store')
                ->with('error', 'Failed to delete store: ' . $e->getMessage());
        }
    }

    public function serviceRadius()
    {
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        return view('store-management.service-radius.index', compact('stores'));
    }

    /**
     * AJAX endpoint for fetching service areas with pagination (DataTables)
     */
    public function getServiceAreas(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $storeId = $request->input('store_id');
        $isActive = $request->input('is_active');

        // Build query
        $query = StoreServiceArea::with('store');

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('area_name', 'like', '%' . $search . '%')
                  ->orWhere('pincode', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('state', 'like', '%' . $search . '%')
                  ->orWhereHas('store', function($storeQuery) use ($search) {
                      $storeQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by store
        if (!empty($storeId)) {
            $query->where('store_id', $storeId);
        }

        // Filter by active status
        if ($isActive !== null && $isActive !== '') {
            $query->where('is_active', $isActive == '1' ? true : false);
        }

        // Get total count before pagination
        $totalRecords = StoreServiceArea::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $serviceAreas = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $serviceAreas->map(function ($serviceArea) {
            $statusBadge = $serviceArea->is_active 
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';

            $coverageAreas = [];
            if ($serviceArea->city) $coverageAreas[] = $serviceArea->city;
            if ($serviceArea->pincode) $coverageAreas[] = 'Pincode: ' . $serviceArea->pincode;
            $coverageText = !empty($coverageAreas) ? implode(', ', $coverageAreas) : 'All Areas';

            return [
                'id' => $serviceArea->id,
                'store_name' => $serviceArea->store->name ?? 'N/A',
                'store_id' => $serviceArea->store_id,
                'area_name' => $serviceArea->area_name ?? 'N/A',
                'city' => $serviceArea->city ?? 'N/A',
                'radius_km' => $serviceArea->radius_km ?? 0,
                'coverage_area' => $coverageText,
                'min_order_amount' => number_format($serviceArea->min_order_amount ?? 0, 2),
                'delivery_charge' => number_format($serviceArea->delivery_charge ?? 0, 2),
                'status' => $statusBadge,
                'is_active' => $serviceArea->is_active,
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show form to create service area
     */
    public function createServiceArea()
    {
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        return view('store-management.service-radius.create', compact('stores'));
    }

    /**
     * Store new service area
     */
    public function storeServiceArea(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'area_name' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'radius_km' => 'required|integer|min:1|max:100',
            'min_order_amount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active') ? true : false;

            StoreServiceArea::create($validated);

            DB::commit();

            return redirect()->route('store-management.service-radius')
                ->with('success', 'Service area created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create service area: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit service area
     */
    public function editServiceArea($id)
    {
        $serviceArea = StoreServiceArea::with('store')->findOrFail($id);
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        return view('store-management.service-radius.edit', compact('serviceArea', 'stores'));
    }

    /**
     * Update service area
     */
    public function updateServiceArea(Request $request, $id)
    {
        $serviceArea = StoreServiceArea::findOrFail($id);

        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'area_name' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'radius_km' => 'required|integer|min:1|max:100',
            'min_order_amount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active') ? true : false;

            $serviceArea->update($validated);

            DB::commit();

            return redirect()->route('store-management.service-radius')
                ->with('success', 'Service area updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update service area: ' . $e->getMessage());
        }
    }

    /**
     * Delete service area
     */
    public function destroyServiceArea($id)
    {
        try {
            $serviceArea = StoreServiceArea::findOrFail($id);
            $serviceArea->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service area deleted successfully.'
                ]);
            }

            return redirect()->route('store-management.service-radius')
                ->with('success', 'Service area deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete service area: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('store-management.service-radius')
                ->with('error', 'Failed to delete service area: ' . $e->getMessage());
        }
    }
}