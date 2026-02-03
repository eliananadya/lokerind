<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Lamaran</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }

        .email-header.accepted {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .email-header.rejected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .email-header.interview {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .email-header.reviewed {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .email-header.finished {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .email-header.withdrawn {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }

        .email-header.invited {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .email-header .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.8;
        }

        .email-body h2 {
            color: #667eea;
            font-size: 22px;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .job-details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .job-details p {
            margin: 10px 0;
            font-size: 15px;
        }

        .job-details strong {
            color: #667eea;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 16px;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-badge.accepted {
            background-color: #10b981;
            color: white;
        }

        .status-badge.rejected {
            background-color: #ef4444;
            color: white;
        }

        .status-badge.interview {
            background-color: #3b82f6;
            color: white;
        }

        .status-badge.reviewed {
            background-color: #f59e0b;
            color: white;
        }

        .status-badge.finished {
            background-color: #8b5cf6;
            color: white;
        }

        .status-badge.withdrawn {
            background-color: #6b7280;
            color: white;
        }

        .status-badge.invited {
            background-color: #06b6d4;
            color: white;
        }

        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .info-box.success {
            background-color: #d1fae5;
            border-left-color: #10b981;
        }

        .info-box.warning {
            background-color: #fef3c7;
            border-left-color: #f59e0b;
        }

        .info-box.danger {
            background-color: #fee2e2;
            border-left-color: #ef4444;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
        }

        .email-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #667eea, transparent);
            margin: 30px 0;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '●';
            position: absolute;
            left: -24px;
            color: #667eea;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        @php
            $statusClass = strtolower($newStatus);
            $statusIcon = '📢';
            $statusText = '';

            switch ($newStatus) {
                case 'Accepted':
                    $statusIcon = '🎉';
                    $statusText = 'DITERIMA';
                    break;
                case 'Rejected':
                    $statusIcon = '😔';
                    $statusText = 'DITOLAK';
                    break;
                case 'Interview':
                    $statusIcon = '📅';
                    $statusText = 'UNDANGAN INTERVIEW';
                    break;
                case 'Reviewed':
                    $statusIcon = '👀';
                    $statusText = 'SEDANG DITINJAU';
                    break;
                case 'Finished':
                    $statusIcon = '✅';
                    $statusText = 'SELESAI';
                    break;
                case 'Withdrawn':
                    $statusIcon = '🚫';
                    $statusText = 'DIBATALKAN';
                    break;
                case 'invited':
                    $statusIcon = '💌';
                    $statusText = 'DIUNDANG';
                    break;
                default:
                    $statusText = strtoupper($newStatus);
            }
        @endphp

        <!-- Header -->
        <div class="email-header {{ $statusClass }}">
            <div class="icon">{{ $statusIcon }}</div>
            <h1>Status Lamaran Diperbarui!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Halo <strong>{{ $application->candidate->user->name }}</strong>,</p>

            <p>Kami ingin memberitahu Anda bahwa status lamaran Anda telah diperbarui.</p>

            <div class="text-center">
                <div class="status-badge {{ $statusClass }}">
                    {{ $statusIcon }} {{ $statusText }}
                </div>
            </div>

            <div class="divider"></div>

            <h2>📋 Detail Lamaran</h2>

            <div class="job-details">
                <p><strong>Posisi:</strong> {{ $application->jobPosting->title }}</p>
                <p><strong>Perusahaan:</strong> {{ $application->jobPosting->company->name }}</p>
                <p><strong>Lokasi:</strong> {{ $application->jobPosting->city->name ?? 'Tidak tersedia' }}</p>
                <p><strong>Tanggal Melamar:</strong> {{ $application->applied_at->format('d F Y, H:i') }} WIB</p>
                <p><strong>Status Baru:</strong> <span
                        style="color: #667eea; font-weight: bold;">{{ $statusText }}</span></p>
            </div>

            @if ($newStatus == 'Accepted')
                <div class="info-box success">
                    <p><strong>🎉 Selamat!</strong></p>
                    <p>• Lamaran Anda telah <strong>DITERIMA</strong></p>
                    <p>• Perusahaan akan segera menghubungi Anda untuk langkah selanjutnya</p>
                    <p>• Pastikan nomor telepon dan email Anda aktif</p>
                    <p>• Siapkan dokumen yang diperlukan</p>
                </div>
            @elseif($newStatus == 'Rejected')
                <div class="info-box danger">
                    <p><strong>😔 Mohon Maaf</strong></p>
                    <p>• Lamaran Anda belum dapat kami proses lebih lanjut</p>
                    <p>• Jangan berkecil hati, masih banyak peluang lainnya</p>
                    <p>• Terus tingkatkan skill dan pengalaman Anda</p>
                    <p>• Coba lamar posisi lain yang sesuai dengan profil Anda</p>
                </div>
            @elseif($newStatus == 'Interview')
                <div class="info-box">
                    <p><strong>📅 Undangan Interview</strong></p>
                    <p>• Selamat! Anda diundang untuk tahap interview</p>
                    <p>• Perusahaan akan menghubungi Anda untuk jadwal interview</p>
                    <p>• Persiapkan diri Anda dengan baik</p>
                    <p>• Pelajari tentang perusahaan dan posisi yang dilamar</p>
                </div>
            @elseif($newStatus == 'Reviewed')
                <div class="info-box warning">
                    <p><strong>👀 Sedang Ditinjau</strong></p>
                    <p>• Lamaran Anda sedang dalam proses peninjauan</p>
                    <p>• Tim rekrutmen sedang mengevaluasi profil Anda</p>
                    <p>• Harap bersabar menunggu update selanjutnya</p>
                </div>
            @elseif($newStatus == 'Finished')
                <div class="info-box success">
                    <p><strong>✅ Proses Selesai</strong></p>
                    <p>• Proses rekrutmen untuk posisi ini telah selesai</p>
                    <p>• Terima kasih atas partisipasi Anda</p>
                    <p>• Jangan lupa berikan rating dan review</p>
                </div>
            @endif

            <div class="divider"></div>

            <h2>📊 Timeline Status</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <strong>Applied</strong> - {{ $application->applied_at->format('d M Y, H:i') }}
                </div>
                @if ($oldStatus != $newStatus)
                    <div class="timeline-item">
                        <strong>{{ $oldStatus }}</strong> → <strong
                            style="color: #667eea;">{{ $newStatus }}</strong>
                    </div>
                @endif
                <div class="timeline-item">
                    <strong>Update Terakhir</strong> - {{ now()->format('d M Y, H:i') }} WIB
                </div>
            </div>

            <div style="text-center; margin: 30px 0;">
                <a href="{{ url('/candidate/aktivitas') }}" class="button">
                    📊 Lihat Detail Lamaran
                </a>
            </div>

            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                Jika Anda memiliki pertanyaan, silakan hubungi perusahaan melalui platform kami atau email langsung ke
                perusahaan.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Platform Pencarian Kerja Terpercaya</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Beranda</a> |
                <a href="{{ url('/candidate/aktivitas') }}">Aktivitas Saya</a> |
                <a href="{{ url('/jobs') }}">Cari Lowongan</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #adb5bd;">
                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
            </p>
            <p style="margin-top: 10px; font-size: 12px; color: #adb5bd;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
