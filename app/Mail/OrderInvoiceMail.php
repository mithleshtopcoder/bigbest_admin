<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $fileName;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $fileName)
    {
        $this->order = $order;
        $this->fileName = $fileName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice for Order #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-invoice',
            with: [
                'order' => $this->order,
                'customer' => $this->order->customer,
                'orderNumber' => $this->order->order_number,
                'totalAmount' => $this->order->total_amount,
                'orderDate' => $this->order->created_at->format('d M Y, h:i A'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filePath = public_path('documents/orders/' . $this->fileName);
        
        if (file_exists($filePath)) {
            return [
                Attachment::fromPath($filePath)
                    ->as('Invoice_' . $this->order->order_number . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
