<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Selesai - {{ $problem->code }}</title>
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
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            margin: 20px 0;
        }
        .success-icon svg {
            width: 80px;
            height: 80px;
            color: #22c55e;
        }
        .congratulations {
            text-align: center;
            margin: 20px 0;
        }
        .congratulations h2 {
            color: #22c55e;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .problem-info {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .problem-info h3 {
            margin-top: 0;
            color: #15803d;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dcfce7;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: #166534;
        }
        .info-value {
            color: #14532d;
            flex: 1;
        }
        .timeline {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .timeline h3 {
            margin-top: 0;
            color: #495057;
            font-size: 18px;
            text-align: center;
        }
        .timeline-item {
            display: flex;
            margin-bottom: 15px;
            position: relative;
        }
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .timeline-content {
            flex: 1;
        }
        .timeline-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .timeline-desc {
            color: #6c757d;
            font-size: 14px;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .action-button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #22c55e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 0 10px;
            font-size: 16px;
        }
        .action-button:hover {
            background-color: #15803d;
        }
        .action-button-secondary {
            background-color: #6c757d;
        }
        .action-button-secondary:hover {
            background-color: #495057;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .highlight-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Laporan Selesai!</h1>
            <p>Proses perbaikan sarana prasarana telah selesai</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Success Icon -->
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Congratulations -->
            <div class="congratulations">
                <h2>Selamat! Proses Selesai</h2>
                <p>Laporan kerusakan <strong>{{ $problem->code }}</strong> telah selesai diproses secara keseluruhan.</p>
            </div>

            <!-- Highlight Box -->
            <div class="highlight-box">
                <strong>✨ Terima kasih telah menggunakan sistem SARANA!</strong><br>
                Sarana dan prasarana kembali tersedia untuk mendukung kegiatan belajar mengajar.
            </div>

            <!-- Problem Information -->
            <div class="problem-info">
                <h3>Informasi Laporan</h3>
                
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

                @if($problem->user_technician_id)
                <div class="info-row">
                    <div class="info-label">Teknisi:</div>
                    <div class="info-value">{{ $problem->technician->name ?? '-' }}</div>
                </div>
                @endif

                @if($problem->admin_id)
                <div class="info-row">
                    <div class="info-label">Admin:</div>
                    <div class="info-value">{{ $problem->admin->name ?? '-' }}</div>
                </div>
                @endif

                @if($problem->user_management_id)
                <div class="info-row">
                    <div class="info-label">Management:</div>
                    <div class="info-value">{{ $problem->management->name ?? '-' }}</div>
                </div>
                @endif

                @if($problem->user_finance_id)
                <div class="info-row">
                    <div class="info-label">Keuangan:</div>
                    <div class="info-value">{{ $problem->finance->name ?? '-' }}</div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Status Final:</div>
                    <div class="info-value">
                        <strong style="color: #22c55e;">✅ SELESAI</strong>
                    </div>
                </div>

                @if($problem->items()->count() > 0)
                <div class="info-row">
                    <div class="info-label">Total Biaya:</div>
                    <div class="info-value">
                        <strong>Rp {{ number_format($problem->items()->sum('price')) }}</strong>
                    </div>
                </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="timeline">
                <h3>📅 Proses Penyelesaian</h3>
                
                <div class="timeline-item">
                    <div class="timeline-icon">📝</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Laporan Dibuat</div>
                        <div class="timeline-desc">{{ \Carbon\Carbon::parse($problem->created_at)->format('d M Y H:i') }}</div>
                    </div>
                </div>

                @if($problem->user_technician_id)
                <div class="timeline-item">
                    <div class="timeline-icon">🔧</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Teknisi Mengerjakan</div>
                        <div class="timeline-desc">{{ $problem->technician->name ?? 'Teknisi' }}</div>
                    </div>
                </div>
                @endif

                @if($problem->user_management_id)
                <div class="timeline-item">
                    <div class="timeline-icon">🛡️</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Disetujui Management</div>
                        <div class="timeline-desc">{{ $problem->management->name ?? 'Management' }}</div>
                    </div>
                </div>
                @endif

                @if($problem->admin_id)
                <div class="timeline-item">
                    <div class="timeline-icon">👤</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Disetujui Admin</div>
                        <div class="timeline-desc">{{ $problem->admin->name ?? 'Admin' }}</div>
                    </div>
                </div>
                @endif

                @if($problem->user_finance_id)
                <div class="timeline-item">
                    <div class="timeline-icon">💳</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Disetujui Keuangan</div>
                        <div class="timeline-desc">{{ $problem->finance->name ?? 'Keuangan' }}</div>
                    </div>
                </div>
                @endif

                <div class="timeline-item">
                    <div class="timeline-icon">🎉</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Proses Selesai</div>
                        <div class="timeline-desc">{{ \Carbon\Carbon::parse($problem->updated_at)->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('problems.show', $problem) }}" class="action-button">
                    📄 Lihat Detail Laporan
                </a>
                <a href="{{ route('dashboard.index') }}" class="action-button action-button-secondary">
                    🏠 Ke Dashboard
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh sistem SARANA</p>
            <p style="margin: 5px 0 0 0;">{{ now()->format('d F Y H:i') }}</p>
            <p style="margin: 10px 0 0 0;">
                <strong>Jangan balas email ini.</strong> Untuk informasi lebih lanjut, hubungi admin sistem.
            </p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #adb5bd;">
                SARANA - Sistem Informasi Sarana & Prasarana Sekolah
            </p>
        </div>
    </div>
</body>
</html>