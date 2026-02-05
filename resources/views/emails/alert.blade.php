<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan - {{ $title ?? 'SARANA' }}</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        .alert-icon {
            text-align: center;
            margin: 20px 0;
        }
        .alert-icon svg {
            width: 60px;
            height: 60px;
            color: #ef4444;
        }
        .alert-content {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-content h3 {
            margin-top: 0;
            color: #991b1b;
            font-size: 18px;
        }
        .alert-urgent {
            background-color: #fee2e2;
            border: 2px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            color: #991b1b;
        }
        .action-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ef4444;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .action-button:hover {
            background-color: #dc2626;
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
            <h1>🚨 Peringatan Penting</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Sistem Informasi Sarana & Prasarana</p>
        </div>

        <div class="email-body">
            <div class="alert-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            @if(isset($urgent))
            <div class="alert-urgent">
                ⚠️ {{ $urgent }}
            </div>
            @endif

            <div class="alert-content">
                <h3>{{ $title ?? 'Peringatan' }}</h3>
                <p>{{ $message ?? 'Perhatian diperlukan untuk masalah ini.' }}</p>
            </div>

            @if(isset($details))
            <div style="background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h4 style="margin-top: 0; color: #495057;">Detail:</h4>
                <p style="color: #6c757d; margin: 0;">{{ $details }}</p>
            </div>
            @endif

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
            <p style="margin: 10px 0 0 0;">
                <strong>Hubungi admin segera jika ini memerlukan perhatian mendesak.</strong>
            </p>
        </div>
    </div>
</body>
</html>