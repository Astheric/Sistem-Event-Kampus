<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 10mm;
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            background: #FFFDF8;
            margin: 0;
            padding: 0;
        }
        .cert-border {
            border: 6px solid #000;
            padding: 15px;
            height: 94%;
            box-sizing: border-box;
            position: relative;
        }
        .cert-inner {
            border: 2px solid #000;
            height: 96%;
            padding: 25px 35px;
            box-sizing: border-box;
            text-align: center;
        }
        .header-brand {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .main-title {
            font-size: 34px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            background: #FFE600;
            border: 3px solid #000;
            padding: 6px 20px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .subtitle {
            font-size: 13px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .recipient-name {
            font-size: 28px;
            font-weight: 900;
            color: #000;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin: 0 auto 15px auto;
            display: inline-block;
            min-width: 400px;
        }
        .event-text {
            font-size: 14px;
            font-weight: bold;
            line-height: 1.5;
            max-width: 650px;
            margin: 0 auto 15px auto;
        }
        .event-name {
            font-size: 18px;
            font-weight: 900;
            display: block;
            margin-top: 5px;
        }
        .cert-footer {
            width: 100%;
            margin-top: 25px;
        }
        .cert-footer td {
            vertical-align: bottom;
        }
        .sign-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sign-line {
            width: 180px;
            border-bottom: 2px solid #000;
            margin: 50px auto 5px auto;
        }
        .sign-name {
            font-size: 12px;
            font-weight: 900;
        }
        .qr-section {
            text-align: center;
        }
        .cert-code {
            font-family: monospace;
            font-weight: 900;
            font-size: 13px;
            background: #FFE600;
            border: 2px solid #000;
            padding: 4px 8px;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="cert-border">
        <div class="cert-inner">
            <div class="header-brand">EVENTHUB • SISTEM EVENT KAMPUS TERPADU</div>
            <div class="main-title">SERTIFIKAT PENGHARGAAN</div>

            <div class="subtitle">DIBERIKAN DENGAN BANGGA KEPADA:</div>
            <div class="recipient-name">{{ $user->name }}</div>

            <div class="event-text">
                Sebagai PESERTA TERVERIFIKASI pada kegiatan:
                <span class="event-name">"{{ $event->title }}"</span>
                diselenggarakan oleh {{ $organization->name }} pada tanggal {{ $event->start_time->format('d F Y') }}.
            </div>

            <table class="cert-footer">
                <tr>
                    <td width="33%" align="center">
                        <div class="sign-title">Penyelenggara Kegiatan,</div>
                        <div class="sign-line"></div>
                        <div class="sign-name">{{ $organization->name }}</div>
                        <div style="font-size: 10px; color: #555;">Ketua Pelaksana</div>
                    </td>
                    <td width="33%" align="center" class="qr-section">
                        <img src="{{ $qrCode }}" width="100" height="100" alt="Verification QR Code">
                        <div class="cert-code">{{ $certificate->certificate_number }}</div>
                        <div style="font-size: 9px; font-weight: bold; margin-top: 3px;">PINDAI UNTUK VERIFIKASI</div>
                    </td>
                    <td width="33%" align="center">
                        <div class="sign-title">Verifikasi Kampus,</div>
                        <div class="sign-line"></div>
                        <div class="sign-name">Administrator EventHub</div>
                        <div style="font-size: 10px; color: #555;">Sistem Terverifikasi</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
