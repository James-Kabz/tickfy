<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2e3b4e;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        li:last-child {
            border-bottom: none;
        }

        li strong {
            color: #2e3b4e;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Congratulations, {{ $ticket->name }}!</h1>
        <p>Your ticket for the event <strong>{{ $eventName }}</strong> is ready. Here are the details:</p>

        <ul>
            <li><strong>Event ID:</strong> {{ $ticket->event_id }}</li>
            <li><strong>Amount Paid:</strong> KES {{ $ticket->price }}</li>
            <li><strong>Quantity:</strong> {{ $ticket->quantity }}</li>
        </ul>

        <p>Enjoy the event!</p>

        <a href="{{ route('events.show', ['event' => $ticket->event_id]) }}" class="button">View Event Details</a>
    </div>

    <footer>
        <p>&copy; 2025 Event Organization. All Rights Reserved.</p>
    </footer>
</body>

</html>
