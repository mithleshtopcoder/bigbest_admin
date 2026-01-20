<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <p>Redirecting to payment...</p>

    <form method="POST" action="{{ route('razorpay.verify') }}" id="paymentForm">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script>
        var options = {
            key: "{{ $razorpayKey }}"
            , amount: "{{ (int) round($order->total_amount * 100) }}"
            , currency: "INR"
            , name: "Your Store Name"
            , description: "POS Order Payment"
            , order_id: "{{ $rzpOrder['id'] }}"
            , handler: function(response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('paymentForm').submit();
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();

    </script>
</body>
</html>
