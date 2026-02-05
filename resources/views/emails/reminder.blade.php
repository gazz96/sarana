<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat - {{ $title ?? 'SARANA' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
        }
        .reminder-icon {
            text-align: center;
            margin: 20px 0;
        }
        .reminder-icon svg {
            width: 60px;
            height: 60px;
            color: #f59e0b;
        }
        .reminder-content {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reminder-content h3 {
            margin-top: 0;
            color: #92400e;
            font-size: 18px;
        }
        .action-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .action-button:hover {
            background-color: #d97706;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>⏰ Pengingat</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Sistem Informasi Sarana & Prasarana</p>
        </div>

        <div class="email-body">
            <div class="reminder-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div class="reminder-content">
                <h3>{{ $title ?? 'Pengingat' }}</h3>
                <p>{{ $message ?? 'Anda memiliki tugas yang perlu ditangani.' }}</p>
            </div>

            @if(isset($action_url) && isset($action_text))
            <div style="text-align: center;">
                <a href="{{ $action_url }}" class="action-button">
                    {{ $action_text }}
                </a>
            </div>
            @endif

            @if(isset($additional_info))
            <p style="margin: 20px 0; color: #6c757d; font-size: 14px;">
                {{ $additional_info }}
            </p>
            @endif
        </div>

        <div class="footer">
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh sistem SARANA</p>
            <p style="margin: 5px 0 0 0;">{{ now()->format('d F Y H:i') }}</p>
        </div>
    </div>
</body>
</html>