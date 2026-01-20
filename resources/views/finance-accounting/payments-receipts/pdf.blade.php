<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Slip - {{ $payment->payment_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .payment-slip {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-item strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }
        .detail-item span {
            color: #666;
        }
        .amount {
            text-align: center;
            padding: 20px;
            background: #f9f9f9;
            border: 2px solid #333;
            margin: 30px 0;
        }
        .amount h2 {
            margin: 0;
            font-size: 36px;
            color: {{ $payment->payment_type === 'payment' ? '#dc3545' : '#28a745' }};
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="payment-slip">
        <div class="header">
            <h1>{{ $companyName }}</h1>
            <p>{{ $payment->payment_type === 'receipt' ? 'RECEIPT' : 'PAYMENT' }} SLIP</p>
        </div>

        <div class="details">
            <div>
                <div class="detail-item">
                    <strong>Transaction ID:</strong>
                    <span>#{{ strtoupper($payment->payment_type === 'receipt' ? 'REC' : 'PAY') }}-{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Payment Number:</strong>
                    <span>{{ $payment->payment_number }}</span>
                </div>
                <div class="detail-item">
                    <strong>Type:</strong>
                    <span>{{ ucfirst($payment->payment_type) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Customer:</strong>
                    <span>{{ $payment->customer?->full_name ?? 'N/A' }}</span>
                </div>
            </div>
            <div>
                <div class="detail-item">
                    <strong>Payment Method:</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Status:</strong>
                    <span>{{ ucfirst($payment->status) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Paid Date:</strong>
                    <span>{{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : '-' }}</span>
                </div>
                <div class="detail-item">
                    <strong>Transaction ID:</strong>
                    <span>{{ $payment->transaction_id ?: '-' }}</span>
                </div>
                @if($payment->order)
                <div class="detail-item">
                    <strong>Order Number:</strong>
                    <span>{{ $payment->order->order_number ?? '-' }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="amount">
            <h2>₹{{ number_format($payment->amount, 2) }}</h2>
            <p style="margin: 10px 0 0 0; color: #666;">{{ $payment->payment_type === 'receipt' ? 'Amount Received' : 'Amount Paid' }}</p>
        </div>

        <div class="footer">
            <p>This is a computer-generated document and does not require a signature.</p>
            <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</body>
</html>
