<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weather Alert Service - Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Weather Alert Service</h1>
    </div>
    <div class="content">
        <h2>Test Email</h2>
        <p>This is a test email from the Weather Alert Service.</p>
        <p>If you're receiving this email, it means that the email configuration is working correctly.</p>
        <p>You can now receive weather alerts when your specified conditions are met.</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Weather Alert Service</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>
