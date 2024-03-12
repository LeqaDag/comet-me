<!DOCTYPE html>
<html>
<head>
    <title>Comet-me</title>
</head>
<body>
    <h3>Dear {{ $details['name'] }},</h3>

    <p>
        {{ $details['body'] }}
        <br>

    </p>
    <p>Start Working Date :{{ $details['start_date'] }}</p>
    <p>End Working Date :{{ $details['end_date'] }}</p>
     
    <p>Best Regards</p>
</body>
</html>