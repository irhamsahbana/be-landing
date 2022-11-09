<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <h3>Verify Your Email Address</h3>

    <p>Hi {{ $name}}</p>
    <p>Thank you for choosing Coach Cube.</p>
    <p>Please click the button below to verify your email</p>

    <p>
        @php
            $token = (string) $token;
            $link = (string) config('app.url');
            $link .= "/verify-email/" . $token;
        @endphp

        <a href="{{ $link }}">Verify Email</a>
    </p>

    <p>
        Ignore this email if you did not request a verification email.
    </p>
</body>
</html>
