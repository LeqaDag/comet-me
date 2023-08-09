<!DOCTYPE html>
<html>
<head>
    <title>Comet-me</title>
</head>
<body>
    <h3>{{ $details['title'] }}</h3>
    <p>
        Dear {{ $details['name'] }},
    </p>
    <p>
        {{ $details['body'] }}
    </p>
    <p>
        To complete the login process, please use the following one-time 2FA code:
    </p>
    <p>Verification Code :{{ $details['code'] }}</p>
     
    <p>Best Regards</p>
</body>
</html>