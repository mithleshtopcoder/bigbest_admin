<?php

namespace App\Http\Controllers\Admin;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\ProductReview;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('party-management.customers.list.index');
    }

    /**
     * AJAX endpoint for fetching customers with pagination
     */
    public function getCustomers(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');

        // Build query
        $query = Customer::withCount('orders')
            ->withSum('orders', 'total_amount');

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Get total count before pagination
        $totalRecords = Customer::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $customers = $query->latest()
            ->skip($start)
            ->take($length)
            ->get();

        // Transform customers to match the expected format
        $data = $customers->map(function ($customer) {
            return [
                'id' => str_pad($customer->id, 3, '0', STR_PAD_LEFT),
                'full_name' => $customer->full_name,
                'email' => $customer->email ?? '-',
                'phone' => $customer->phone ?? '-',
                'orders_count' => $customer->orders_count ?? 0,
                'total_spent' => number_format($customer->orders_sum_total_amount ?? 0, 2),
                'status' => $customer->status ?? 'active',
                'customer_id' => $customer->id, // Keep original ID for routes
            ];
        });

        // If no customers, provide empty collection
        if ($data->isEmpty() && empty($search)) {
            // You can add dummy data here if needed for testing
            $data = collect([]);
            $filteredRecords = 0;
            $totalRecords = 0;
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }
    
    public function create()
    {
        return view('party-management.customers.list.create');
    }

    public function show(Customer $customer)
{
    
    $customer->load(['orders', 'addresses']);

    return view('party-management.customers.list.show', compact('customer'));
}
    
    public function orders()
    {
        return view('admin.customers.orders');
    }
    
    public function addresses()
    {
        $addresses = CustomerAddress::with('customer')
        ->latest()
        ->paginate(10);
        
        return view(
            'party-management.customers.addresses.index',
            compact('addresses')
        );
    }
    
    

    public function createAddress()
{
    $customers = Customer::all();
    return view('party-management.customers.addresses.create', compact('customers'));
}

public function viewAddress(CustomerAddress $address)
{
    $customers = Customer::all();
    return view('party-management.customers.addresses.edit', compact('address', 'customers'));
}

public function storeAddress(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'type' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'pincode' => 'required|string',
        'landmark' => 'nullable|string',
    ]);

    CustomerAddress::create($request->all());

    return redirect()->route('party-management.customers.addresses')->with('success', 'Address created successfully.');
}

public function updateAddress(Request $request, CustomerAddress $address)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'type' => 'required|in:home,office,other',
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'pincode' => 'required|string|max:10',
        'landmark' => 'nullable|string',
    ]);

    $address->update($request->all());
    return redirect()->route('party-management.customers.addresses')->with('success', 'Address updated successfully.');
}

// Delete address
public function deleteAddress(CustomerAddress $address)
{
    $address->delete();
    return redirect()->route('party-management.customers.addresses')->with('success', 'Address deleted successfully.');
}

    public function wallet()
    {
        return view('party-management.customers.wallet.index');
    }

    public function createWallet()
    {
        return view('party-management.customers.wallet.create');
    }

    public function loyaltyPoints()
    {
        return view('party-management.customers.loyalty-points.index');
    }

    public function createLoyaltyPoints()
    {
        return view('party-management.customers.loyalty-points.create');
    }

    public function feedback()
{
    $feedbacks = ProductReview::with([
            'customer:id,full_name',
            'product:id,name',
            'order:id,order_number'
        ])
        ->latest()
        ->paginate(10);

    return view('party-management.customers.feedback.index', compact('feedbacks'));
}
}