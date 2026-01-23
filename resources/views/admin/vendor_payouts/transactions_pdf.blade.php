<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Transactions - {{ $vendor->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            /* Important for ₹ symbol in PDFs */
            font-size: 12px;
            line-height: 1.4;
        }

        h2,
        p {
            margin: 0;
            padding: 0;
        }

        h2 {
            margin-bottom: 5px;
        }

        p {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
            font-weight: bold;
        }

        td {
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

    </style>
</head>
<body>
    <h2>Vendor: {{ $vendor->name }}</h2>
    <p>Email: {{ $vendor->email }}</p>
    <p>Mobile: {{ $vendor->mobile_number }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount (₹)</th>
                <th>Payment Method</th>
                <th>Reference / Transaction ID</th>
                <th>Note</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            <tr>
                <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                <td class="text-right">&#8377;{{ number_format($tx->amount, 2) }}</td>
                <td>{{ ucfirst($tx->payment_method) }}</td>
                <td>{{ $tx->reference_no }}</td>
                <td>{{ $tx->note ?? '-' }}</td>
                <td>{{ ucfirst($tx->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
