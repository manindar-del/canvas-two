<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome Email</title>
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

        ._logo img{
            width:35%;
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
                <h1 class="_headline">Welcome</h1>
                <p>Subject : Thanks for your Registration</p>
                <p>Dear {{$user['company_name']}}</p>
                {{-- @if (!empty($user))
                    <p><strong>User Name:</strong> {{ $user->name }}</p>
                @elseif (Auth::check())
                    <p><strong>User Name:</strong> {{ Auth::user()->name }}</p>
                @endif
                <p><strong>Password:</strong> **********</p> --}}

                <p>Greetings from Canvas Vacations Global Co., Ltd.</p>
                <p>We have received your application and our team will take less than 24 hrs for validation,on approval a confirmation email will be sent to you.</p>
                {{-- <ol>
                    <li>GST CERTIFICATE (IF APPLICABLE)</li>
                    <li>CERTIFICATION OF INCORPORATION </li>
                </ol> --}}
                <p>Thank you for giving us an opportunity to serve you..</p>
                <p>Regards,<br>Team Canvas</p>
            </div>
            <div class="_footer">
                <p><strong>Need more help?</strong></p>
                <p><a href="mailto:online@canvasvacations.net">We’re here, ready to talk</a></p>
            </div>
            <div class="_logo">
                <img src="http://canvasvacations.net/assets/img/logo.png" alt="">
            </div>
        </div>
    </div>
</body>
</html>