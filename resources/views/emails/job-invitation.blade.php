<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Melamar Pekerjaan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #14489b, #1e3992);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .email-body {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .greeting strong {
            color: #14489b;
        }

        .intro-text {
            font-size: 15px;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .job-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-left: 5px solid #14489b;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }

        .job-title {
            font-size: 22px;
            color: #14489b;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .job-detail {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
            color: #555;
        }

        .job-detail-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            color: #14489b;
        }

        .job-description {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            max-height: 150px;
            overflow: hidden;
            position: relative;
        }

        .custom-message {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .custom-message-title {
            font-size: 16px;
            font-weight: 700;
            color: #856404;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .custom-message-text {
            font-size: 14px;
            color: #856404;
            line-height: 1.6;
            font-style: italic;
        }

        .cta-section {
            text-align: center;
            margin: 35px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #14489b, #1e3992);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(20, 72, 155, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(20, 72, 155, 0.4);
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .info-box p {
            font-size: 14px;
            color: #0d47a1;
            margin: 0;
            line-height: 1.6;
        }

        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .email-footer p {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #14489b;
            text-decoration: none;
            font-size: 14px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 25px 0;
        }

        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px 20px;
            }

            .email-header {
                padding: 30px 20px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .job-title {
                font-size: 20px;
            }

            .cta-button {
                padding: 14px 30px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Undangan Khusus Untuk Anda!</h1>
            <p>Peluang karir menarik menanti Anda</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Greeting -->
            <div class="greeting">
                Halo, <strong>{{ $candidateName }}</strong>! 👋
            </div>

            <!-- Intro -->
            <div class="intro-text">
                Kami dari <strong>{{ $companyName }}</strong> tertarik dengan profil dan kualifikasi Anda.
                Kami dengan senang hati mengundang Anda untuk melamar posisi yang sesuai dengan keahlian Anda.
            </div>

            <!-- Job Card -->
            <div class="job-card">
                <div class="job-title">{{ $jobTitle }}</div>

                <div class="job-detail">
                    <span class="job-detail-icon">🏢</span>
                    <span><strong>Perusahaan:</strong> {{ $companyName }}</span>
                </div>

                <div class="job-detail">
                    <span class="job-detail-icon">📍</span>
                    <span><strong>Lokasi:</strong> {{ $jobLocation }}</span>
                </div>

                <div class="job-detail">
                    <span class="job-detail-icon">💰</span>
                    <span><strong>Gaji:</strong> {{ $salary }}</span>
                </div>

                @if ($jobDescription)
                    <div class="job-description">
                        {!! Str::limit(strip_tags($jobDescription), 200) !!}
                    </div>
                @endif
            </div>

            <!-- Custom Message -->
            @if ($customMessage)
                <div class="custom-message">
                    <div class="custom-message-title">
                        💬 Pesan dari {{ $companyName }}
                    </div>
                    <div class="custom-message-text">
                        "{{ $customMessage }}"
                    </div>
                </div>
            @endif

            <!-- Divider -->
            <div class="divider"></div>

            <!-- CTA -->
            <div class="cta-section">
                <a href="{{ $applyUrl }}" class="cta-button">
                    📝 Lihat Detail & Lamar Sekarang
                </a>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <p>
                    <strong>💡 Tips:</strong> Pastikan profil Anda sudah lengkap dan CV terbaru sudah diupload
                    untuk meningkatkan peluang diterima. Jangan lewatkan kesempatan emas ini!
                </p>
            </div>

            <!-- Closing -->
            <div class="intro-text" style="margin-top: 30px;">
                Kami sangat menantikan aplikasi Anda dan berharap dapat segera bertemu dengan Anda.
            </div>

            <div class="intro-text">
                Salam hangat,<br>
                <strong>Tim {{ $companyName }}</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>LOKERIND</strong> - Platform Pencarian Kerja Terpercaya</p>
            <p>{{ $companyAddress }}</p>

            <div class="divider"></div>

            <p style="font-size: 12px; color: #999;">
                Email ini dikirim secara otomatis. Jika Anda tidak tertarik dengan posisi ini,
                Anda dapat mengabaikan email ini.
            </p>

            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                © {{ date('Y') }} LOKERIND. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
