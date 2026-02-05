<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $eventName }}</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
        }
        .problem-info {
            background-color: #f8f9fa;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .problem-info h3 {
            margin-top: 0;
            color: #3b82f6;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
            flex: 1;
        }
        .action-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .action-button:hover {
            background-color: #1d4ed8;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-0 { background-color: #6c757d; color: white; }
        .status-1 { background-color: #f59e0b; color: white; }
        .status-2 { background-color: #3b82f6; color: white; }
        .status-3 { background-color: #22c55e; color: white; }
        .status-4 { background-color: #ef4444; color: white; }
        .status-5 { background-color: #8b5cf6; color: white; }
        .status-6 { background-color: #06b6d4; color: white; }
        .status-7 { background-color: #f97316; color: white; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🔧 SARANA</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Sistem Informasi Sarana & Prasarana</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Greeting -->
            <div class="greeting">
                Halo,<br>
                Anda menerima notifikasi terkait laporan kerusakan sarana prasarana.
            </div>

            <!-- Problem Information -->
            @if($problem)
            <div class="problem-info">
                <h3>{{ $eventName }}</h3>
                
                <div class="info-row">
                    <div class="info-label">Kode Laporan:</div>
                    <div class="info-value"><strong>{{ $problem->code }}</strong></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($problem->date)->format('d F Y H:i') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Pelapor:</div>
                    <div class="info-value">{{ $problem->user->name ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $problem->status }}">
                            {{ \App\Models\Problem::$STATUS[$problem->status] ?? 'Unknown' }}
                        </span>
                    </div>
                </div>

                @if(isset($data['old_status']) || isset($data['new_status']))
                <div class="info-row">
                    <div class="info-label">Perubahan:</div>
                    <div class="info-value">
                        @if(isset($data['old_status']))
                            {{ \App\Models\Problem::$STATUS[$data['old_status']] ?? '-' }}
                        @endif
                        @if(isset($data['old_status']) && isset($data['new_status']))
                            →
                        @endif
                        @if(isset($data['new_status']))
                            {{ \App\Models\Problem::$STATUS[$data['new_status']] ?? '-' }}
                        @endif
                    </div>
                </div>
                @endif

                @if(isset($data['technician_id']))
                <div class="info-row">
                    <div class="info-label">Teknisi:</div>
                    <div class="info-value">{{ \App\Models\User::find($data['technician_id'])->name ?? '-' }}</div>
                </div>
                @endif

                @if(isset($data['approved_by']))
                <div class="info-row">
                    <div class="info-label">Disetujui Oleh:</div>
                    <div class="info-value">{{ $data['approved_by'] }}</div>
                </div>
                @endif

                @if(isset($data['cancelled_by']))
                <div class="info-row">
                    <div class="info-label">Dibatalkan Oleh:</div>
                    <div class="info-value">{{ $data['cancelled_by'] }}</div>
                </div>
                @endif
            </div>
            @endif

            <!-- Additional Context -->
            @if($event === 'problem_submitted')
            <p style="margin: 20px 0;">
                Laporan kerusakan telah diajukan dan menunggu untuk diproses oleh tim teknisi.
                Silakan cek sistem untuk informasi lebih lanjut.
            </p>
            @elseif($event === 'problem_accepted')
            <p style="margin: 20px 0;">
                Laporan kerusakan telah diterima dan sedang dalam proses pengerjaan.
                Estimasi waktu penyelesaian akan diinformasikan lebih lanjut.
            </p>
            @elseif($event === 'problem_finished')
            <p style="margin: 20px 0;">
                Perbaikan telah selesai dilakukan dan menunggu persetujuan management.
                Terima kasih atas kesabaran Anda.
            </p>
            @elseif($event === 'problem_approved_finance')
            <p style="margin: 20px 0;">
                🎉 <strong>Selamat!</strong> Proses laporan kerusakan telah selesai secara keseluruhan.
                Terima kasih telah menggunakan sistem SARANA.
            </p>
            @elseif($event === 'problem_cancelled')
            <p style="margin: 20px 0;">
                Laporan kerusakan telah dibatalkan. Silakan hubungi admin jika Anda memerlukan informasi lebih lanjut.
            </p>
            @endif

            <!-- Action Button -->
            @if($problem)
            <div style="text-align: center;">
                <a href="{{ route('problems.show', $problem) }}" class="action-button">
                    Lihat Detail Laporan
                </a>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh sistem SARANA</p>
            <p style="margin: 5px 0 0 0;">{{ now()->format('d F Y H:i') }}</p>
            <p style="margin: 10px 0 0 0;">
                <em>Jangan balas email ini. Untuk informasi lebih lanjut, hubungi admin sistem.</em>
            </p>
        </div>
    </div>
</body>
</html>