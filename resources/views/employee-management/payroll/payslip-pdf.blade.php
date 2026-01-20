<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Payslip - {{ $payroll->employeeProfile->employee_code }}</title>
    <style>
        /* PDF Page Setup */
        @page {
            size: A4;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            background: #fff;
        }

        /* Full Page Background Wrapper */
        .page-wrapper {
            position: relative;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
        }

        .template-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: fill;
        }

        /* Main Content Container */
        .container {
            position: relative;
            z-index: 1;
            padding: 40mm 15mm 20mm 15mm;
            /* Adjust top padding to clear your template's logo area */
        }

        /* Typography & Titles */
        .payslip-header {
            text-align: center;
            margin-bottom: 25px;
            text-transform: uppercase;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
        }

        .payslip-header h1 {
            font-size: 22px;
            letter-spacing: 2px;
            color: #1a1a1a;
        }

        /* Info Grid */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #555;
            width: 35%;
        }

        .value {
            color: #000;
            width: 65%;
        }

        /* Salary Table Styling */
        .salary-container {
            width: 100%;
            display: table;
            border: 1px solid #000;
            table-layout: fixed;
        }

        .salary-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            border-right: 1px solid #000;
        }

        .salary-column:last-child {
            border-right: none;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inner-table th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
            font-size: 10px;
        }

        .inner-table td {
            padding: 6px 8px;
            border-bottom: 0.5px solid #eee;
        }

        .amount-col {
            text-align: right;
            font-family: 'DejaVu Sans', sans-serif;
            /* For currency symbols */
        }

        /* Summary & Totals */
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .net-salary-section {
            margin-top: 20px;
            border: 2px solid #000;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .net-salary-grid {
            width: 100%;
            display: table;
        }

        .net-salary-label {
            display: table-cell;
            font-size: 14px;
            font-weight: bold;
            vertical-align: middle;
        }

        .net-salary-value {
            display: table-cell;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #0046ad;
            vertical-align: middle;
        }

        /* Signature Area */
        .signature-wrapper {
            margin-top: 60px;
            width: 100%;
        }

        .sig-box {
            float: left;
            width: 30%;
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #333;
            margin: 0 10px 5px 10px;
        }

        .footer {
            position: absolute;
            bottom: 15mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        /* Clearfix */
        .group:after {
            content: "";
            display: table;
            clear: both;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;

            /* VIEW PDF (Base64 background) */
            @if( !empty($templateBase64)) background-image: url('{{ $templateBase64 }}');

            /* DOWNLOAD PDF (normal image) */
            @elseif($settings && $settings->template) background-image: url("{{ public_path('images/settings/' . $settings->template) }}");
            @endif background-repeat: no-repeat;
            background-size: cover;
            background-position: center top;
        }

    </style>
</head>
<body>

    <div class="page-wrapper">
        {{-- Background Template --}}
        @if($settings && $settings->template)
        <img src="{{ public_path('images/settings/' . $settings->template) }}" class="template-bg">
        @endif

        <div class="container">
            <div class="payslip-header">
                {{-- <h2>pay slip</h2> --}}
                <br>
                <br>
                <p>Pay Slip For the month of {{ $payroll->payroll_period }}</p>
            </div>

            {{-- Employee & Company Info Grid --}}
            <table class="info-table">
                <tr>
                    <td width="50%">
                        <table>
                            <tr>
                                <td class="label">Employee Name</td>
                                <td class="value">: {{ $payroll->employeeProfile->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Employee ID</td>
                                <td class="value">: {{ $payroll->employeeProfile->employee_code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Designation</td>
                                <td class="value">: {{ $payroll->employeeProfile->designation->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Department</td>
                                <td class="value">: {{ $payroll->employeeProfile->department->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table>
                            <tr>
                                <td class="label">Working Days</td>
                                <td class="value">: {{ $payroll->working_days }}</td>
                            </tr>
                            <tr>
                                <td class="label">Days Present</td>
                                <td class="value">: {{ $payroll->present_days }}</td>
                            </tr>
                            <tr>
                                <td class="label">Pay Date</td>
                                <td class="value">: {{ $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->format('d-m-Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status</td>
                                <td class="value">: <span style="color: green;">{{ strtoupper($payroll->status) }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- Main Salary Breakdown Table --}}
            <div class="salary-container">
                {{-- Earnings --}}
                <div class="salary-column">
                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th>Earnings Description</th>
                                <th class="amount-col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Basic Salary</td>
                                <td class="amount-col">{{ \App\Helpers\MyHelper::formatCurrency($payroll->basic_salary) }}</td>
                            </tr>
                            @foreach($payroll->items->where('item_type','allowance') as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td class="amount-col">{{ \App\Helpers\MyHelper::formatCurrency($item->amount) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td style="border-top: 1px solid #000;">Gross Earnings</td>
                                <td class="amount-col" style="border-top: 1px solid #000;">{{ \App\Helpers\MyHelper::formatCurrency($payroll->gross_salary) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Deductions --}}
                <div class="salary-column">
                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th>Deductions Description</th>
                                <th class="amount-col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payroll->items->where('item_type','deduction') as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td class="amount-col">{{ \App\Helpers\MyHelper::formatCurrency($item->amount) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999; padding: 20px;">No Deductions</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td style="border-top: 1px solid #000;">Total Deductions</td>
                                <td class="amount-col" style="border-top: 1px solid #000;">{{ \App\Helpers\MyHelper::formatCurrency($payroll->total_deductions) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Net Salary Banner --}}
            <div class="net-salary-section">
                <div class="net-salary-grid">
                    <div class="net-salary-label">
                        NET PAYABLE AMOUNT
                        <div style="font-size: 9px; font-weight: normal; color: #666; margin-top: 4px;">
                            (In Words: {{ \App\Helpers\MyHelper::numberToWords($payroll->net_salary) }} Only)
                        </div>
                    </div>
                    <div class="net-salary-value" style="font-family: 'DejaVu Sans', sans-serif;">
                        {{ \App\Helpers\MyHelper::formatCurrency($payroll->net_salary) }}
                    </div>
                </div>
            </div>

            {{-- Signatures --}}
            <div class="signature-wrapper group">
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <p>Employee Signature</p>
                </div>
                <div class="sig-box" style="float: right;">
                    <div class="sig-line"></div>
                    <p>Authorized Signatory</p>
                </div>
            </div>

            <div class="footer">
                <p>This is a computer-generated document and requires no physical signature.</p>
                <p>Printed on: {{ date('d-M-Y H:i:s') }}</p>
            </div>
        </div>
    </div>

</body>
</html>
