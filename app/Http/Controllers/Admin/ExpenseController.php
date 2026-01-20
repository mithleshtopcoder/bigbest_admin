<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Store;
use App\Models\Option;
use App\Models\OptionMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index()
    {
        return view('finance-accounting.expenses.index');
    }

    /**
     * Get expenses data for DataTables
     */
    public function getExpenses(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $categoryId = $request->input('category_id');
        $statusId = $request->input('status_id');
        $storeId = $request->input('store_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Build query
        $query = Expense::with([
            'store:id,name',
            'category:id,name',
            'paymentMethod:id,name',
            'statusOption:id,name',
            'createdBy:id,name'
        ]);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('expense_number', 'like', '%' . $search . '%')
                  ->orWhere('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('reference_number', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Filter by status
        if (!empty($statusId)) {
            $query->where('status_id', $statusId);
        }

        // Filter by store
        if (!empty($storeId)) {
            $query->where('store_id', $storeId);
        }

        // Filter by date range
        if (!empty($dateFrom)) {
            $query->where('expense_date', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->where('expense_date', '<=', $dateTo);
        }

        // Get total count before pagination
        $totalRecords = Expense::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $expenses = $query->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $expenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'expense_number' => $expense->expense_number,
                'title' => $expense->title,
                'category' => $expense->category ? $expense->category->name : '-',
                'store' => $expense->store ? $expense->store->name : '-',
                'amount' => number_format($expense->amount, 2),
                'expense_date' => $expense->expense_date->format('Y-m-d'),
                'payment_method' => $expense->paymentMethod ? $expense->paymentMethod->name : '-',
                'status' => $expense->statusOption ? $expense->statusOption->name : '-',
                'status_id' => $expense->status_id,
                'created_by' => $expense->createdBy ? $expense->createdBy->name : '-',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $stores = Store::where('status', 1)->orderBy('name')->get(['id', 'name']);
        
        // Get option masters for expense category, payment method, and status
        $masters = OptionMaster::whereIn('name', [
            'Expense Category',
            'Payment Method',
            'Status'
        ])->get()->keyBy('name');

        $categories = collect();
        $paymentMethods = collect();
        $statuses = collect();

        if (isset($masters['Expense Category'])) {
            $categories = Option::where('option_master_id', $masters['Expense Category']->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        if (isset($masters['Payment Method'])) {
            $paymentMethods = Option::where('option_master_id', $masters['Payment Method']->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        if (isset($masters['Status'])) {
            $statuses = Option::where('option_master_id', $masters['Status']->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        return view('finance-accounting.expenses.create', compact(
            'stores',
            'categories',
            'paymentMethods',
            'statuses'
        ));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'category_id' => 'required|exists:options,id',
            'payment_method_id' => 'required|exists:options,id',
            'status_id' => 'required|exists:options,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $expense = new Expense();
            $expense->expense_number = Expense::generateExpenseNumber();
            $expense->store_id = $validated['store_id'] ?? null;
            $expense->category_id = $validated['category_id'];
            $expense->payment_method_id = $validated['payment_method_id'];
            $expense->status_id = $validated['status_id'];
            $expense->title = $validated['title'];
            $expense->description = $validated['description'] ?? null;
            $expense->amount = $validated['amount'];
            $expense->expense_date = $validated['expense_date'];
            $expense->reference_number = $validated['reference_number'] ?? null;
            $expense->notes = $validated['notes'] ?? null;
            $expense->created_by = Auth::id();
            $expense->save();

            DB::commit();

            return redirect()->route('finance-accounting.expenses.index')
                ->with('success', 'Expense created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create expense: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified expense.
     */
    public function show(string $id)
    {
        $expense = Expense::with([
            'store',
            'category',
            'paymentMethod',
            'statusOption',
            'createdBy'
        ])->findOrFail($id);

        return view('finance-accounting.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        $stores = Store::where('status', 1)->orderBy('name')->get(['id', 'name']);
        
        // Get option masters for expense category, payment method, and status
        $masters = OptionMaster::whereIn('name', [
            'Expense Category',
            'Payment Method',
            'Status'
        ])->get()->keyBy('name');

        $categories = collect();
        $paymentMethods = collect();
        $statuses = collect();

        if (isset($masters['Expense Category'])) {
            $categories = Option::where('option_master_id', $masters['Expense Category']->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        if (isset($masters['Payment Method'])) {
            $paymentMethods = Option::where('option_master_id', $masters['Payment Method']->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        if (isset($masters['Status'])) {
            $statuses = Option::where('option_master_id', $masters['Status']->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        return view('finance-accounting.expenses.edit', compact(
            'expense',
            'stores',
            'categories',
            'paymentMethods',
            'statuses'
        ));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'category_id' => 'required|exists:options,id',
            'payment_method_id' => 'required|exists:options,id',
            'status_id' => 'required|exists:options,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $expense->store_id = $validated['store_id'] ?? null;
            $expense->category_id = $validated['category_id'];
            $expense->payment_method_id = $validated['payment_method_id'];
            $expense->status_id = $validated['status_id'];
            $expense->title = $validated['title'];
            $expense->description = $validated['description'] ?? null;
            $expense->amount = $validated['amount'];
            $expense->expense_date = $validated['expense_date'];
            $expense->reference_number = $validated['reference_number'] ?? null;
            $expense->notes = $validated['notes'] ?? null;
            $expense->save();

            DB::commit();

            return redirect()->route('finance-accounting.expenses.index')
                ->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update expense: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense: ' . $e->getMessage()
            ], 500);
        }
    }
}
