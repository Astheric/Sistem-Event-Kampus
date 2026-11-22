<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $certificate->certificate_number }}</title>
    <style>
        @page { margin: 8mm; size: a4 landscape; }
        html, body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        .frame {
            border: 1px solid #cbd5e1;
            padding: 5px;
            height: 188mm;
        }
        .inner {
            border: 2px solid #4823cd;
            padding: 28px 40px 20px;
            text-align: center;
            height: 172mm;
        }
        .brand {
            font-size: 11px;
            font-weight: 700;
            color: #4823cd;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 22px;
        }
        .title {
            font-size: 34px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
        }
        .subtitle {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 26px;
        }
        .recipient {
            font-size: 30px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            margin: 0 auto 22px auto;
            display: inline-block;
            min-width: 360px;
        }
        .event-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.7;
            max-width: 620px;
            margin: 0 auto;
        }
        .event-name {
            display: block;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 8px 0;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .footer-table td { vertical-align: bottom; }
        .sign-title {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sign-line {
            width: 170px;
            border-bottom: 1px solid #94a3b8;
            margin: 44px auto 8px auto;
        }
        .sign-name { font-size: 12px; font-weight: 700; color: #0f172a; }
        .sign-role { font-size: 10px; color: #64748b; margin-top: 2px; }
        .qr-section { text-align: center; }
        .cert-code {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            color: #4823cd;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            padding: 4px 10px;
            display: inline-block;
            margin-top: 6px;
            border-radius: 4px;
        }
        .verify-note { font-size: 9px; color: #64748b; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="frame">
        <div class="inner">
            <div class="brand">EventHub &middot; Sertifikat Kegiatan Kampus</div>
            <div class="title">Sertifikat Penghargaan</div>
            <div class="subtitle">Diberikan kepada</div>

            <div class="recipient">{{ $user->name }}</div>

            <div class="event-text">
                Atas partisipasi sebagai peserta terverifikasi pada kegiatan
                <span class="event-name">{{ $event->title }}</span>
                Diselenggarakan oleh {{ $organization->name }} pada {{ $event->start_time->translatedFormat('d F Y') }}.
            </div>

            <table class="footer-table">
                <tr>
                    <td width="33%" align="center">
                        <div class="sign-title">Penyelenggara kegiatan</div>
                        <div class="sign-line"></div>
                        <div class="sign-name">{{ $organization->name }}</div>
                        <div class="sign-role">Ketua pelaksana</div>
                    </td>
                    <td width="34%" align="center" class="qr-section">
                        <img src="{{ $qrCode }}" width="92" height="92" alt="QR verifikasi sertifikat">
                        <div><span class="cert-code">{{ $certificate->certificate_number }}</span></div>
                        <div class="verify-note">Pindai untuk verifikasi</div>
                    </td>
                    <td width="33%" align="center">
                        <div class="sign-title">Verifikasi sistem</div>
                        <div class="sign-line"></div>
                        <div class="sign-name">Administrator EventHub</div>
                        <div class="sign-role">Sistem terverifikasi</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
