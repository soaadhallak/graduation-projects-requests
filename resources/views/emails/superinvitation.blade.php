<!DOCTYPE html>
<html dir="ltr">
<head>
    <style>
        .button {
            background-color: #6366f1;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            font-weight: bold;
        }
        .footer { font-size: 12px; color: #836969ff; margin-top: 30px; text-align: center; }
    </style>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee;">
        <div style="text-align: right; color: #777;">ProjectFlow Invite</div>
        <hr>
        <h2>Hello ,</h2>
        <p>You have been invited to join the Graduation Project Management Platform as a Supervisor.
            Please note that this invitation link will expire in 2 days.
        </p>

        <div style="margin: 30px 0; text-align: center;">
            <a href="{{ $url }}" class="button" style="color: white;">Accept Invite</a>
        </div>

        <p>Sincerely,<br><strong>ProjectFlow Support</strong></p>

        <div class="footer">
            © 2026 ProjectFlow. All rights reserved.
        </div>
    </div>
</body>
</html>
