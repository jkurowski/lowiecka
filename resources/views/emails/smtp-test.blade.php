<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>SMTP Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #dddddd;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #dddddd;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>SMTP Test Email</h1>
        </div>
        <div class="content">
            <p>To jest testowy email wysłany z aplikacji.</p>
            <p>Jeśli otrzymałeś ten email, to twoje ustawienia SMTP są poprawne.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} DeveloCRM.</p>
        </div>
    </div>
</body>

</html>
