<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            font-family: monospace, Arial, sans-serif;
            font-size: 12px;
            width: 80mm;
            background: #fff;
        }

        .invoice-container {
            width: 100%;
            padding: 0 5mm;
            margin: 0;
        }

        p,
        table {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 2px 0;
        }

        th {
            text-align: left;
            border-bottom: 1px dashed #000;
        }

        .text-right {
            text-align: right;
        }

        .total-line {
            border-top: 1px dashed #000;
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 2px 0;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body,
            html,
            .invoice-container {
                width: 80mm;
                padding: 0 5mm;
            }
        }

    </style>
</head>
<body>
    <div class="invoice-container" id="invoice-content">

        {{-- Company Info --}}
        <p style="font-weight:bold;">{{ $settings->company_name ?? 'My Store' }}</p>
        <p style="font-size:10px;">
            {{ $settings->address ?? '123, Market Street' }}<br>
            Phone: {{ $settings->phone ?? '0000000000' }}
        </p>
        <hr>

        {{-- Invoice Details --}}
        <p style="font-size:12px;">
            <strong>Invoice #:</strong> {{ $order->order_number }}<br>
            <strong>Date:</strong> {{ $order->created_at->format('d M, Y H:i') }}<br>
            <strong>Customer:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name ?? '' }}<br>
            <strong>Phone:</strong> {{ $order->customer->phone }}<br>
            <strong>Payment:</strong> {{ ucfirst($order->payment_method) }}
        </p>
        <hr>

        {{-- Items --}}
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }} @if($item->variant_name)<br><small>{{ $item->variant_name }}</small>@endif</td>
                    <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>

        {{-- Totals --}}
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td class="text-right">₹{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td class="text-right">₹{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Other Charges</td>
                <td class="text-right">₹{{ number_format($order->shipping_charge, 2) }}</td>
            </tr>
            <tr class="total-line">
                <td>Total</td>
                <td class="text-right">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
        <hr>

        <p style="text-align:center; font-size:12px;">Thank you for your purchase!</p>

    </div>

    <script>
        function printInvoice() {
            window.print();
            window.onafterprint = function() {
                window.location.href = "{{ route('new-order.index', 'pos') }}";
            };
        }

        window.onload = printInvoice;

    </script>
</body>
</html>
