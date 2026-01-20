<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice - {{ $orderNumber }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .content {
            margin: 30px 0;
        }
        .order-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-info h2 {
            color: #4CAF50;
            margin-top: 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .message {
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>RG Organic Mart</h1>
            <p>Fresh Organic Products - Farm to Door</p>
        </div>

        <div class="content">
            <h2>Dear {{ $customer->first_name ?? 'Valued Customer' }},</h2>
            
            <p>Thank you for your order! We're pleased to confirm that your order has been processed successfully.</p>

            <div class="order-info">
                <h2>Order Details</h2>
                <div class="info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value"><strong>#{{ $orderNumber }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ $orderDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Status:</span>
                    <span class="info-value">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Status:</span>
                    <span class="info-value">{{ ucwords(str_replace('_', ' ', $order->payment_status)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Amount:</span>
                    <span class="info-value amount">₹{{ number_format($totalAmount, 2) }}</span>
                </div>
            </div>

            <div class="message">
                <p><strong>📎 Invoice Attached</strong></p>
                <p>Your detailed invoice is attached to this email. You can download and save it for your records.</p>
            </div>

            <p>If you have any questions about your order, please don't hesitate to contact our customer support team.</p>

            <p style="text-align: center;">
                <a href="{{ url('/orders/' . $order->id) }}" class="button">View Order Details</a>
            </p>
        </div>

        <div class="footer">
            <p>Thank you for choosing RG Organic Mart!</p>
            <p>For any queries, please contact us at: info@rgorganicmart.com</p>
            <p>&copy; {{ date('Y') }} RG Organic Mart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

