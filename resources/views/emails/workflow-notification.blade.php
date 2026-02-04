<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi SARPRAS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .notification-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .notification-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .problem-details {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .problem-code {
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 10px;
        }
        .problem-info {
            margin: 5px 0;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .status-draft { background-color: #6c757d; color: white; }
        .status-diajukan { background-color: #ffc107; color: #000; }
        .status-proses { background-color: #17a2b8; color: white; }
        .status-selesai { background-color: #28a745; color: white; }
        .status-batal { background-color: #dc3545; color: white; }
        
        .action-button {
            display: inline-block;
            background-color: #007bff;
            color: white !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
            font-weight: bold;
            text-align: center;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .timestamp {
            font-style: italic;
            color: #6c757d;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistem Informasi SARPRAS</h1>
            <p>Notifikasi Workflow Problem</p>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $notifiable->name }}</strong>,</p>
            
            <div class="notification-box">
                <div class="notification-title">
                    📢 {{ $data['event_name'] }}
                </div>
                <p>{{ $data['message'] }}</p>
            </div>

            <div class="problem-details">
                <div class="problem-code">
                    Kode: {{ $data['problem_code'] }}
                </div>
                
                <div class="problem-info">
                    <strong>Masalah:</strong> {{ $data['problem_issue'] }}
                </div>
                
                <div class="problem-info">
                    <strong>Status:</strong> 
                    <span class="status-badge status-{{ getStatusClass($data['problem_status']) }}">
                        {{ getStatusText($data['problem_status']) }}
                    </span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $data['link'] }}" class="action-button">
                    Lihat Detail Problem
                </a>
            </div>

            <div class="timestamp">
                {{ \Carbon\Carbon::parse($data['timestamp'])->locale('id')->translatedFormat('l, d F Y H:i') }}
            </div>
        </div>

        <div class="footer">
            <p>Ini adalah notifikasi otomatis dari Sistem Informasi SARPRAS.</p>
            <p>Jangan balas email ini. Untuk pertanyaan, silakan hubungi admin sistem.</p>
            <p>&copy; {{ date('Y') }} SARPRAS System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

@php
function getStatusClass($status) {
    $classes = [
        0 => 'draft',
        1 => 'diajukan', 
        2 => 'proses',
        3 => 'selesai',
        4 => 'batal',
        5 => 'selesai',
        6 => 'selesai',
        7 => 'selesai',
    ];
    return $classes[$status] ?? 'draft';
}

function getStatusText($status) {
    $texts = [
        0 => 'DRAFT',
        1 => 'DIAJUKAN',
        2 => 'PROSES',
        3 => 'SELESAI DIKERJAKAN',
        4 => 'DIBATALKAN',
        5 => 'MENUNGGU PERSETUJUAN MANAGEMENT',
        6 => 'MENUNGGU PERSETUJUAN ADMIN', 
        7 => 'MENUNGGU PERSETUJUAN KEUANGAN',
    ];
    return $texts[$status] ?? 'UNKNOWN';
}
@endphp