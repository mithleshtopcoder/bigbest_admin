<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .company-info h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }
        .company-info p {
            margin: 3px 0;
            font-size: 11px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .invoice-info p {
            margin: 3px 0;
            font-size: 11px;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .billing-info, .shipping-info {
            width: 48%;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .info-item {
            margin: 5px 0;
            font-size: 11px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #333;
            color: #fff;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-section {
            margin-top: 20px;
            margin-left: auto;
            width: 300px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 11px;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 14px;
            padding-top: 10px;
            border-top: 2px solid #333;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .payment-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #333;
        }
        .payment-info p {
            margin: 3px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>{{ $store->name ?? 'RG Organic Mart' }}</h1>
                <p>{{ $store->address ?? '' }}</p>
                <p>{{ $store->city ?? '' }}, {{ $store->state ?? '' }} - {{ $store->pincode ?? '' }}</p>
                <p>Phone: {{ $store->phone ?? '' }}</p>
                <p>Email: {{ $store->email ?? '' }}</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                @if($order->confirmed_at)
                <p><strong>Confirmed:</strong> {{ $order->confirmed_at->format('d M Y, h:i A') }}</p>
                @endif
            </div>
        </div>

        <div class="details-section">
            <div class="billing-info">
                <div class="section-title">Bill To</div>
                @if($customer)
                <div class="info-item"><span class="info-label">Name:</span> {{ $customer->full_name }}</div>
                <div class="info-item"><span class="info-label">Email:</span> {{ $customer->email }}</div>
                <div class="info-item"><span class="info-label">Phone:</span> {{ $customer->phone }}</div>
                @if($customer->address)
                <div class="info-item"><span class="info-label">Address:</span> {{ $customer->address }}</div>
                <div class="info-item"><span class="info-label">City:</span> {{ $customer->city }}, {{ $customer->state }}</div>
                <div class="info-item"><span class="info-label">Pincode:</span> {{ $customer->pincode }}</div>
                @endif
                @else
                <div class="info-item">Walk-in Customer</div>
                @endif
            </div>
            <div class="shipping-info">
                <div class="section-title">Ship To</div>
                @if($deliveryAddress)
                <div class="info-item"><span class="info-label">Name:</span> {{ $deliveryAddress->contact_name }}</div>
                <div class="info-item"><span class="info-label">Phone:</span> {{ $deliveryAddress->contact_phone }}</div>
                <div class="info-item"><span class="info-label">Address:</span> {{ $deliveryAddress->address }}</div>
                @if($deliveryAddress->landmark)
                <div class="info-item"><span class="info-label">Landmark:</span> {{ $deliveryAddress->landmark }}</div>
                @endif
                <div class="info-item"><span class="info-label">City:</span> {{ $deliveryAddress->city }}, {{ $deliveryAddress->state }}</div>
                <div class="info-item"><span class="info-label">Pincode:</span> {{ $deliveryAddress->pincode }}</div>
                @else
                <div class="info-item">Same as billing address</div>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Tax</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                        <br><small>{{ $item->variant_name }}</small>
                        @endif
                    </td>
                    <td>{{ $item->product_sku }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->tax_amount ?? 0, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->tax_amount > 0)
            <div class="summary-row">
                <span>Tax (GST):</span>
                <span>₹{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            @if($order->shipping_charge > 0)
            <div class="summary-row">
                <span>Shipping:</span>
                <span>₹{{ number_format($order->shipping_charge, 2) }}</span>
            </div>
            @endif
            @if($order->discount_amount > 0)
            <div class="summary-row">
                <span>Discount:</span>
                <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @if($coupon)
            <div class="summary-row" style="font-size: 10px; color: #666;">
                <span>Coupon ({{ $coupon->code }}):</span>
                <span></span>
            </div>
            @endif
            @endif
            @if($order->wallet_amount_used > 0)
            <div class="summary-row">
                <span>Wallet Used:</span>
                <span>-₹{{ number_format($order->wallet_amount_used, 2) }}</span>
            </div>
            @endif
            @if($order->loyalty_points_used > 0)
            <div class="summary-row">
                <span>Loyalty Points:</span>
                <span>-₹{{ number_format($order->loyalty_points_used / 100, 2) }}</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="payment-info">
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
            @if($order->delivery_date)
            <p><strong>Expected Delivery:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}</p>
            @endif
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any queries, please contact us at {{ $store->email ?? 'support@rgorganicmart.com' }}</p>
        </div>
    </div>
</body>
</html>

