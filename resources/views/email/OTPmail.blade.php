<!-- resources/views/email/OTPmail.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            color: #333;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Your OTP Code</h2>
        <p>Dear User,</p>
        <p>Your One-Time Password (OTP) is:</p>
        <p class="otp">{{ $details['otp'] }}</p>
        <p>This OTP is valid for 10 minutes. Please do not share it with anyone.</p>
        <p>Thank you!</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
