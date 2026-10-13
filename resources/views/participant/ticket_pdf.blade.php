<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $registration->registration_code }}</title>
    <style>
        @page {
            margin: 15mm;
            size: a5 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .ticket-container {
            border: 4px solid #000;
            padding: 15px;
            position: relative;
        }
        .ticket-header {
            background-color: #FFE600;
            border-bottom: 3px solid #000;
            padding: 12px;
            margin: -15px -15px 15px -15px;
        }
        .logo-box {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -1px;
        }
        .tag-status {
            float: right;
            border: 2px solid #000;
            background: #fff;
            padding: 3px 8px;
            font-weight: 900;
            font-size: 11px;
        }
        .event-title {
            font-size: 20px;
            font-weight: 900;
            margin: 5px 0 3px 0;
            line-height: 1.2;
        }
        .org-name {
            font-size: 12px;
            font-weight: bold;
            color: #555;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            border: 2px solid #000;
            padding: 8px;
            font-size: 11px;
            background: #F4F0EA;
        }
        .info-label {
            font-size: 9px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            display: block;
        }
        .info-val {
            font-size: 12px;
            font-weight: bold;
        }
        .bottom-section {
            width: 100%;
        }
        .qr-box {
            float: right;
            border: 3px solid #000;
            padding: 8px;
            background: #fff;
            text-align: center;
        }
        .code-display {
            font-family: monospace;
            font-size: 18px;
            font-weight: 900;
            background: #FFE600;
            border: 2px solid #000;
            padding: 6px 12px;
            display: inline-block;
            margin-top: 5px;
        }
        .footer-bar {
            clear: both;
            margin-top: 15px;
            border-top: 2px solid #000;
            padding-top: 5px;
            font-size: 9px;
            font-weight: bold;
            font-family: monospace;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <span class="tag-status">{{ $registration->status }}</span>
            <div class="logo-box">⚡ EventHub • E-TICKET RESMI</div>
        </div>

        <div class="event-title">{{ $registration->event->title }}</div>
        <div class="org-name">Penyelenggara: {{ $registration->event->organization->name }}</div>

        <table class="info-table">
            <tr>
                <td width="25%">
                    <span class="info-label">Nama Peserta</span>
                    <span class="info-val">{{ $registration->user->name }}</span>
                </td>
                <td width="25%">
                    <span class="info-label">Tipe Tiket</span>
                    <span class="info-val">{{ $registration->ticket->name }}</span>
                </td>
                <td width="25%">
                    <span class="info-label">Waktu Acara</span>
                    <span class="info-val">{{ $registration->event->start_time->format('d M Y, H:i') }}</span>
                </td>
                <td width="25%">
                    <span class="info-label">Lokasi</span>
                    <span class="info-val">{{ $registration->event->venue ? $registration->event->venue->name : 'Online' }}</span>
                </td>
            </tr>
        </table>

        <table class="bottom-section">
            <tr>
                <td valign="top">
                    <span class="info-label">KODE REGISTRASI RESMI</span>
                    <div class="code-display">{{ $registration->registration_code }}</div>
                    <p style="font-size: 10px; color: #444; margin-top: 8px;">
                        Tunjukkan tiket ini kepada panitia saat check-in.<br>
                        Kode QR unik dan hanya dapat dipindai satu kali.
                    </p>
                </td>
                <td align="right" valign="top">
                    <div class="qr-box">
                        <img src="{{ $qrCode }}" width="110" height="110" alt="QR Code">
                        <div style="font-size: 8px; font-family: monospace; font-weight: bold; margin-top: 3px;">SCAN CHECK-IN</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-bar">
            EVENTHUB KAMPUS — SISTEM TIKET TERVERIFIKASI • BR-03 PROTECTED
        </div>
    </div>
</body>
</html>
