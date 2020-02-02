<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cacellation Expiry Notification</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }
        ._doc {
            padding: 100px 0;
            background-color: #f4f4f4;
            box-shadow: inset 0 200px #ffa73b;
            color: #555;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.2;
        }
        ._doc a {
            color: #ffa73b;
        }
        ._doc p,
        ._doc ol {
            margin-top: 0;
            margin-bottom: 20px;
        }
        ._doc p:last-child {
            margin-bottom: 0;
        }
        ._doc strong {
            color: #111;
            font-weight: normal;
        }
        ._grid {
            max-width: 600px;
            margin: 0 auto;
        }
        ._header {
            /* position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 200px;
            background-color: #ffa73b; */
        }
        ._body {
            z-index: 1;
            position: relative;
            padding: 60px 30px;
            background-color: #fff;
            border-radius: 8px;
        }
        ._headline {
            margin: 0;
            padding-bottom: 30px;
            color: #111;
            font-size: 36px;
            font-weight: normal;
            text-align: center;
        }
        ._footer {
            margin: 30px 0;
            padding: 30px;
            background-color: #ffecd1;
            border-radius: 8px;
            text-align: center;
        }
        ._logo {
            margin: 30px 0;
            text-align: center;
        }
        ._logo__img {
            max-width: 128px;
        }
    </style>
</head>
<body>
    <div class="_doc">
        <div class="_header">
            {{--  --}}
        </div>
        <div class="_grid">
            <div class="_body">
                <h1 class="_headline">Cancellation Expiry Deadline</h1>
                <p>Dear {{ $user->name }},</p>
                <p>Please note that the cancellation policy for the following booking will expire tomorrow:</p>
                <ul>
                    <li><strong>Booking ID:</strong> span{{ $booking->BookingId }}</li>
                </ul>
                <p>Please login to your dashboard if you wish to cancel the booking.</p>
                <p>
                    Regards,
                    <br>
                    Team {{ config('app.name') }}
                </p>
            </div>
            <div class="_footer">
                <p><strong>Need more help?</strong></p>
                <p><a href="mailto:onlie@canvasvacations.net">We’re here, ready to talk</a></p>
            </div>
            <div class="_logo">
                <img class="_logo__img" src="{{ asset('assets/img/logo.png') }}" alt="">
            </div>
        </div>
    </div>
</body>
</html>