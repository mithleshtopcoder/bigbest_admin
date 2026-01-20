<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class LedgerController extends Controller
{
    public function storeLedger()
    {
        return view('finance-accounting.store-ledger.index');
    }
    
    public function supplierLedger()
    {
        return view('finance-accounting.supplier-ledger.index');
    }
    
    public function customerLedger()
    {
        return view('finance-accounting.customer-ledger.index');
    }

    public function reports()
    {
        return view('finance-accounting.customer-ledger.index');
    }

    public function expenses()
    {
        return view('finance-accounting.expenses.index');
    }

    public function storeExpense(Request $request)
    {
        // Static design only
        return redirect()->route('finance-accounting.expenses');
    }

    public function payments()
{
    $payments = Payment::with('customer')
        ->latest()
        ->paginate(10);

    return view(
        'finance-accounting.payments-receipts.index',
        compact('payments')
    );
}

    /**
     * Show payment slip details
     */
    public function showPayment($id)
    {
        $payment = Payment::with(['customer', 'order'])->findOrFail($id);
        return view('finance-accounting.payments-receipts.show', compact('payment'));
    }

    /**
     * Download payment slip as PDF
     */
    public function downloadPayment($id)
    {
        try {
            $payment = Payment::with(['customer', 'order'])->findOrFail($id);
            
            $data = [
                'payment' => $payment,
                'companyName' => config('app.name', 'Company Name'),
            ];

            $pdf = Pdf::loadView('finance-accounting.payments-receipts.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            
            $fileName = ($payment->payment_type === 'receipt' ? 'Receipt' : 'Payment') . '_' . $payment->payment_number . '.pdf';
            
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->route('finance-accounting.payments-receipts')
                ->with('error', 'Failed to generate payment slip: ' . $e->getMessage());
        }
    }

    /**
     * View payment slip in browser (for printing)
     */
    public function viewPayment($id)
    {
        try {
            $payment = Payment::with(['customer', 'order'])->findOrFail($id);
            return view('finance-accounting.payments-receipts.view', compact('payment'));
        } catch (\Exception $e) {
            return redirect()->route('finance-accounting.payments-receipts')
                ->with('error', 'Payment not found.');
        }
    }

    public function receipts()
    {
        return view('admin.ledger.receipts');
    }

    public function taxReports()
    {
        return view('admin.ledger.tax-reports');
    }
}