<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NexusRides Payment Receipt</title>
    <style>
        @page { margin: 42px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #17283d; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.55; }
        .topline { height: 5px; background: #006cff; margin: -42px -42px 32px; }
        .header { display: table; width: 100%; padding-bottom: 24px; border-bottom: 1px solid #dfe5ec; }
        .brand, .receipt-label { display: table-cell; vertical-align: top; }
        .brand { font-size: 21px; font-weight: bold; color: #0b132b; }
        .brand span { color: #006cff; }
        .brand small { display: block; margin-top: 5px; color: #68778b; font-size: 10px; font-weight: normal; }
        .receipt-label { text-align: right; }
        .receipt-label strong { display: block; color: #0b132b; font-size: 18px; }
        .receipt-label span { color: #68778b; font-size: 10px; }
        .status { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 20px; background: #e8f7ef; color: #187044; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .amount-box { margin: 24px 0; padding: 18px 20px; background: #f3f7fc; border-left: 4px solid #006cff; }
        .amount-label { color: #68778b; font-size: 10px; text-transform: uppercase; }
        .amount { margin-top: 3px; color: #0b132b; font-size: 26px; font-weight: bold; }
        .section-title { margin: 22px 0 8px; color: #0b132b; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 9px 0; border-bottom: 1px solid #edf0f4; vertical-align: top; }
        td:first-child { width: 38%; color: #68778b; }
        td:last-child { color: #17283d; font-weight: bold; }
        .footer { margin-top: 32px; padding-top: 14px; border-top: 1px solid #dfe5ec; color: #68778b; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="topline"></div>
    <header class="header">
        <div class="brand">Nexus<span>Rides</span><small>Vehicle hire and management</small></div>
        <div class="receipt-label">
            <strong>Payment receipt</strong>
            <span>Receipt #NR-PAY-{{ str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) }}</span><br>
            <span class="status">Payment completed</span>
        </div>
    </header>

    <section class="amount-box">
        <div class="amount-label">Amount paid</div>
        <div class="amount">UGX {{ number_format((float) $payment->amount, 0) }}</div>
    </section>

    <div class="section-title">Customer</div>
    <table>
        <tr><td>Name</td><td>{{ $payment->user->name }}</td></tr>
        <tr><td>Email</td><td>{{ $payment->user->email }}</td></tr>
    </table>

    <div class="section-title">Booking</div>
    <table>
        <tr><td>Booking reference</td><td>{{ $payment->booking->booking_reference }}</td></tr>
        <tr><td>Vehicle</td><td>{{ $payment->booking->vehicle->name }}</td></tr>
        <tr><td>Rental period</td><td>{{ $payment->booking->pickup_date->format('d M Y') }} to {{ $payment->booking->return_date->format('d M Y') }}</td></tr>
    </table>

    <div class="section-title">Payment details</div>
    <table>
        <tr><td>Transaction ID</td><td>{{ $payment->transaction_id ?? 'Payment #' . $payment->id }}</td></tr>
        <tr><td>Payment method</td><td>{{ $payment->method_label }}</td></tr>
        <tr><td>Paid on</td><td>{{ $payment->paid_at?->format('d M Y, h:i A') ?? $payment->created_at->format('d M Y, h:i A') }}</td></tr>
    </table>

    <div class="footer">Thank you for choosing NexusRides. For assistance, contact info@nexusrides.ug.</div>
</body>
</html>
