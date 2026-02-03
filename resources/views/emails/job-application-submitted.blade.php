<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran Berhasil Dikirim</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-header .icon {
            font-size: 60px;
            margin-bottom: 15px;
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
            background-color: #ffc107;
            color: #000;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 15px 0;
        }

        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #0d47a1;
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
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">✅</div>
            <h1>Lamaran Berhasil Dikirim!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Halo <strong>{{ $candidate->name }}</strong>,</p>

            <p>Terima kasih telah melamar pekerjaan melalui platform kami. Lamaran Anda telah berhasil dikirim dan
                sedang dalam proses peninjauan oleh perusahaan.</p>

            <div class="divider"></div>

            <h2>📋 Detail Lamaran</h2>

            <div class="job-details">
                <p><strong>Posisi:</strong> {{ $job->title }}</p>
                <p><strong>Perusahaan:</strong> {{ $job->company->name }}</p>
                <p><strong>Lokasi:</strong> {{ $job->city->name ?? 'Tidak tersedia' }}</p>
                <p><strong>Gaji:</strong> Rp {{ number_format($job->salary, 0, ',', '.') }}</p>
                <p><strong>Tanggal Melamar:</strong> {{ $application->applied_at->format('d F Y, H:i') }} WIB</p>
            </div>

            <p><strong>Status Lamaran:</strong></p>
            <div class="status-badge">⏳ PENDING</div>

            <div class="info-box">
                <p><strong>ℹ️ Informasi Penting:</strong></p>
                <p>• Lamaran Anda sedang ditinjau oleh tim rekrutmen {{ $job->company->name }}</p>
                <p>• Anda akan menerima notifikasi email jika ada update status lamaran</p>
                <p>• Pastikan email dan nomor telepon Anda aktif</p>
                <p>• Periksa secara berkala halaman "Aktivitas Saya" untuk update terbaru</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/candidate/aktivitas') }}" class="button">
                    📊 Lihat Status Lamaran
                </a>
            </div>

            <div class="divider"></div>

            <p><strong>Tips:</strong></p>
            <ul style="color: #6c757d; font-size: 14px;">
                <li>Siapkan dokumen pendukung (CV, sertifikat, portofolio)</li>
                <li>Pelajari lebih lanjut tentang perusahaan dan posisi yang dilamar</li>
                <li>Pastikan profil Anda sudah lengkap dan up-to-date</li>
                <li>Aktifkan notifikasi untuk mendapat update real-time</li>
            </ul>

            <p style="margin-top: 30px;">Semoga berhasil! 🎉</p>

            <p style="color: #6c757d; font-size: 14px; margin-top: 20px;">
                Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Platform Pencarian Kerja Terpercaya</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Beranda</a> |
                <a href="{{ url('/candidate/aktivitas') }}">Aktivitas Saya</a> |
                <a href="{{ url('/contact') }}">Hubungi Kami</a>
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
