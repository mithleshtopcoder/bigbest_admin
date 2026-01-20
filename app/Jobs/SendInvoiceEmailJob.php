<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderInvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120]; // Retry after 30s, 60s, 120s
    public $timeout = 60; // 1 minute timeout

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Order $order,
        public string $fileName
    ) {
        // Set queue name for better organization
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Only send email for online orders
            if ($this->order->order_source !== 'online') {
                Log::info('Skipping email for non-online order #' . $this->order->order_number);
                return;
            }

            // Reload order to ensure we have fresh data
            $this->order->refresh();
            $this->order->load('customer');

            // Check if customer has email
            if (!$this->order->customer || !$this->order->customer->email) {
                Log::warning('Cannot send invoice email: Customer email not found for order #' . $this->order->order_number);
                return;
            }

            // Verify file exists
            $filePath = public_path('documents/orders/' . $this->fileName);
            if (!file_exists($filePath)) {
                Log::error('Invoice file not found: ' . $filePath . ' for order #' . $this->order->order_number);
                throw new \Exception('Invoice file not found: ' . $this->fileName);
            }

            // Send email
            Mail::to($this->order->customer->email)->send(new OrderInvoiceMail($this->order, $this->fileName));

            Log::info('Invoice email sent successfully to ' . $this->order->customer->email . ' for order #' . $this->order->order_number);
        } catch (\Exception $e) {
            Log::error('SendInvoiceEmailJob failed for order #' . $this->order->order_number . ': ' . $e->getMessage());
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendInvoiceEmailJob permanently failed for order #' . $this->order->order_number . ': ' . $exception->getMessage());
        
        // Optionally notify admin
        // You can add notification logic here if needed
    }
}
