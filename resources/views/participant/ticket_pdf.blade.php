<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $registration->registration_code }}</title>
    <style>
        @page { margin: 12mm; size: a5 landscape; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .ticket {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            background: #4823cd;
            color: #ffffff;
            padding: 14px 18px;
        }
        .brand {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .brand-sub {
            font-size: 10px;
            color: #ddd6fe;
            margin-top: 2px;
        }
        .status {
            float: right;
            background: #ffffff;
            color: #4823cd;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .body { padding: 18px; }
        .event-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px 0;
            line-height: 1.3;
        }
        .org-name {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 16px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 16px;
        }
        .info-table td {
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            vertical-align: top;
            background: #f8fafc;
        }
        .info-label {
            display: block;
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }
        .info-val {
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
        }
        .bottom { width: 100%; border-collapse: collapse; }
        .code-label {
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .code-display {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #4823cd;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            padding: 8px 12px;
            display: inline-block;
            margin-top: 6px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }
        .hint {
            font-size: 10px;
            color: #64748b;
            margin-top: 10px;
            line-height: 1.5;
        }
        .qr-box {
            border: 1px solid #e2e8f0;
            padding: 10px;
            background: #ffffff;
            text-align: center;
            border-radius: 8px;
        }
        .qr-caption {
            font-size: 8px;
            font-weight: 600;
            color: #64748b;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <span class="status">{{ $registration->status }}</span>
            <div class="brand">EventHub</div>
            <div class="brand-sub">E-Ticket Resmi</div>
        </div>

        <div class="body">
            <div class="event-title">{{ $registration->event->title }}</div>
            <div class="org-name">{{ $registration->event->organization->name }}</div>

            <table class="info-table">
                <tr>
                    <td width="25%">
                        <span class="info-label">Peserta</span>
                        <span class="info-val">{{ $registration->user->name }}</span>
                    </td>
                    <td width="25%">
                        <span class="info-label">Tipe tiket</span>
                        <span class="info-val">{{ $registration->ticket->name }}</span>
                    </td>
                    <td width="25%">
                        <span class="info-label">Waktu</span>
                        <span class="info-val">{{ $registration->event->start_time->format('d M Y, H:i') }}</span>
                    </td>
                    <td width="25%">
                        <span class="info-label">Lokasi</span>
                        <span class="info-val">{{ $registration->event->venue ? $registration->event->venue->name : 'Online' }}</span>
                    </td>
                </tr>
            </table>

            <table class="bottom">
                <tr>
                    <td valign="top">
                        <span class="code-label">Kode registrasi</span><br>
                        <div class="code-display">{{ $registration->registration_code }}</div>
                        <p class="hint">Tunjukkan tiket ini kepada panitia saat check-in.<br>Kode QR hanya dapat dipindai satu kali.</p>
                    </td>
                    <td align="right" valign="top" width="140">
                        <div class="qr-box">
                            <img src="{{ $qrCode }}" width="110" height="110" alt="QR Code">
                            <div class="qr-caption">Scan untuk check-in</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="footer">EventHub Kampus &middot; Sistem tiket terverifikasi</div>
        </div>
    </div>
</body>
</html>
