<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120]; // Retry after 30s, 60s, 120s
    public $timeout = 120; // 2 minutes timeout

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Order $order,
        public bool $sendEmail = true
    ) {
        // Set queue name for better organization
        $this->onQueue('invoices');
    }

    /**
     * Execute the job.
     */
    public function handle(InvoiceService $invoiceService): void
    {
        try {
            // Reload order to ensure we have fresh data
            $this->order->refresh();
            
            // Check if invoice already exists
            if ($this->order->file_name && file_exists(public_path('documents/orders/' . $this->order->file_name))) {
                Log::info('Invoice already exists for order #' . $this->order->order_number);
                
                // If email should be sent and invoice exists, dispatch email job
                if ($this->sendEmail && $this->order->order_source === 'online') {
                    SendInvoiceEmailJob::dispatch($this->order, $this->order->file_name);
                }
                return;
            }

            // Generate invoice PDF
            $fileName = $invoiceService->generateInvoice($this->order);

            if ($fileName) {
                // Update order with file name (without triggering events)
                $this->order->withoutEvents(function () use ($fileName) {
                    $this->order->update(['file_name' => $fileName]);
                });

                Log::info('Invoice generated successfully for order #' . $this->order->order_number . ': ' . $fileName);

                // Dispatch email job if needed
                if ($this->sendEmail && $this->order->order_source === 'online') {
                    SendInvoiceEmailJob::dispatch($this->order, $fileName);
                }
            } else {
                throw new \Exception('Invoice generation returned null for order #' . $this->order->order_number);
            }
        } catch (\Exception $e) {
            Log::error('GenerateInvoiceJob failed for order #' . $this->order->order_number . ': ' . $e->getMessage());
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateInvoiceJob permanently failed for order #' . $this->order->order_number . ': ' . $exception->getMessage());
        
        // Optionally notify admin or update order status
        // You can add notification logic here if needed
    }
}
