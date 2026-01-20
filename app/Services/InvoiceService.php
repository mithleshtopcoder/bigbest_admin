<?php

namespace App\Services;

use App\Models\Order;
use App\Jobs\GenerateInvoiceJob;
use App\Jobs\SendInvoiceEmailJob;
use App\Mail\OrderInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    /**
     * Generate invoice PDF for an order
     *
     * @param Order $order
     * @return string|null File name of the generated PDF
     */
    public function generateInvoice(Order $order): ?string
    {
        try {
            // Load order with all necessary relationships
            $order->load([
                'customer',
                'store',
                'deliveryAddress',
                'items.product',
                'items.productVariant',
                'coupon'
            ]);

            // Ensure directory exists
            $directory = public_path('documents/orders');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Generate file name
            $fileName = 'invoice_' . $order->order_number . '_' . date('YmdHis') . '.pdf';
            $filePath = $directory . '/' . $fileName;

            // Generate PDF
            $pdf = Pdf::loadView('invoices.order', [
                'order' => $order,
                'customer' => $order->customer,
                'store' => $order->store,
                'deliveryAddress' => $order->deliveryAddress,
                'items' => $order->items,
                'coupon' => $order->coupon,
            ]);

            // Save PDF
            $pdf->save($filePath);

            return $fileName;
        } catch (\Exception $e) {
            \Log::error('Invoice generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send invoice email to customer
     *
     * @param Order $order
     * @param string $fileName
     * @return bool
     */
    public function sendInvoiceEmail(Order $order, string $fileName): bool
    {
        try {
            // Only send email for online orders
            if ($order->order_source !== 'online') {
                return false;
            }

            // Load customer relationship
            $order->load('customer');

            // Check if customer has email
            if (!$order->customer || !$order->customer->email) {
                \Log::warning('Cannot send invoice email: Customer email not found for order #' . $order->order_number);
                return false;
            }

            // Send email
            Mail::to($order->customer->email)->send(new OrderInvoiceMail($order, $fileName));

            \Log::info('Invoice email sent successfully to ' . $order->customer->email . ' for order #' . $order->order_number);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send invoice email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate invoice and send email (if online order)
     * 
     * @deprecated Use dispatchGenerateInvoice() for better performance
     * @param Order $order
     * @param bool $sendEmail
     * @return string|null File name of the generated PDF
     */
    public function generateAndSendInvoice(Order $order, bool $sendEmail = true): ?string
    {
        $fileName = $this->generateInvoice($order);
        
        if ($fileName && $sendEmail) {
            $this->sendInvoiceEmail($order, $fileName);
        }

        return $fileName;
    }

    /**
     * Dispatch job to generate invoice asynchronously (Recommended)
     * 
     * @param Order $order
     * @param bool $sendEmail
     * @return void
     */
    public function dispatchGenerateInvoice(Order $order, bool $sendEmail = true): void
    {
        GenerateInvoiceJob::dispatch($order, $sendEmail);
    }

    /**
     * Dispatch job to send invoice email asynchronously (Recommended)
     * 
     * @param Order $order
     * @param string $fileName
     * @return void
     */
    public function dispatchSendInvoiceEmail(Order $order, string $fileName): void
    {
        SendInvoiceEmailJob::dispatch($order, $fileName);
    }
}

