<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
</head>
<body>
    <h1>Thank you for your payment, {{ $customerDetails['name'] }}!</h1>
    <p>Your payment was successful. Here are the details:</p>

    <ul>
        <li><strong>Phone Number:</strong> {{ $customerDetails['phone_number'] }}</li>
        <li><strong>Email:</strong> {{ $customerDetails['email'] }}</li>
        <li><strong>Amount Paid:</strong> KES {{ $callbackData['CallbackMetadata']['Item'][0]['Value'] ?? 'N/A' }}</li>
        <li><strong>Transaction Date:</strong> {{ now()->toDayDateTimeString() }}</li>
    </ul>

    <p>We hope you enjoy the event!</p>
</body>
</html>
