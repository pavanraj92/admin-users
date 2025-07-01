<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Welcome to {{ config('app.name') }}</title>
</head>

<body style="background:#f6f6f6; margin:0; padding:0;">
    <div>
        <table width="600" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff"
            style="border:1px solid #dddddd;">
            <tr>
                <td style="padding:20px; font-family:Arial, sans-serif; font-size:16px; color:#4a5a5a;">
                    <p>Hello {{ $fullName }},</p>

                    <p>Your account has been created. Here are your login details:</p>
                    <ul>
                        <li><strong>Email:</strong> {{ $email }}</li>
                        <li><strong>Password:</strong> {{ $password }}</li>
                    </ul>
                    <p>Please login and change your password after first login.</p>
                </td>
            </tr>
            <tr>
                <td
                    style="background-color:#e5ebeb; color:#4a5a5a; font-size:12px; font-family:Arial, sans-serif; text-align:center; padding:10px;">
                    Powered by <a href="{{ url('/') }}"
                        style="color:#0b5aa4; text-decoration:none;">{{ config('app.name', 'Dotsquares') }}</a>.
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
