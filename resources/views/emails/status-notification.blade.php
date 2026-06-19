<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title }}</title>
<style>
  body { margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI',Arial,sans-serif; }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .hdr { background:linear-gradient(135deg,#0a0f1e 0%,#1e3a5f 100%); padding:32px; text-align:center; }
  .hdr img { height:36px; }
  .hdr h2 { color:#fff; font-size:20px; margin:16px 0 4px; }
  .hdr p { color:rgba(255,255,255,0.6); font-size:13px; margin:0; }
  .body { padding:32px; }
  .greeting { font-size:16px; font-weight:600; color:#1e293b; margin-bottom:12px; }
  .msg { font-size:14px; color:#475569; line-height:1.7; white-space:pre-wrap; }
  .info-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px 20px; margin:20px 0; }
  .info-row { display:flex; justify-content:space-between; font-size:13px; padding:6px 0; border-bottom:1px solid #f1f5f9; }
  .info-row:last-child { border:none; }
  .info-label { color:#94a3b8; font-weight:500; }
  .info-val { color:#1e293b; font-weight:600; font-family:monospace; }
  .btn { display:block; text-align:center; margin:24px auto 0; background:#3b82f6; color:#fff; padding:13px 32px; border-radius:10px; text-decoration:none; font-weight:700; font-size:14px; max-width:200px; }
  .ftr { background:#f8fafc; padding:20px 32px; text-align:center; font-size:11px; color:#94a3b8; line-height:1.6; }
</style>
</head>
<body>
<div class="wrap">
  <div class="hdr">
    <div style="display:inline-block;background:rgba(255,255,255,0.1);padding:10px 20px;border-radius:8px">
      <strong style="color:#60a5fa;font-size:18px">HRD</strong><strong style="color:#fff;font-size:18px">PRO</strong>
      <span style="color:rgba(255,255,255,0.5);font-size:12px;margin-left:8px">InJourney Airports</span>
    </div>
    <h2>{{ $title }}</h2>
    <p>Sistem Rekrutmen PKL — Batch 2025</p>
  </div>
  <div class="body">
    <div class="greeting">Halo, {{ $applicant->name }} 👋</div>
    <div class="msg">{{ strip_tags(str_replace('**', '', $message)) }}</div>
    <div class="info-box">
      <div class="info-row">
        <span class="info-label">Nama</span>
        <span class="info-val">{{ $applicant->name }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">NIM</span>
        <span class="info-val">{{ $applicant->nim }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Kode Akses</span>
        <span class="info-val">{{ $applicant->code }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-val" style="color:{{ $applicant->status === 'accepted' ? '#22c55e' : ($applicant->status === 'rejected' ? '#ef4444' : '#f59e0b') }}">
          {{ strtoupper($applicant->status) }}
        </span>
      </div>
      @if($applicant->location)
      <div class="info-row">
        <span class="info-label">Penempatan</span>
        <span class="info-val">{{ $applicant->location === 'kantor' ? 'Head Office' : 'Terminal Ops' }}</span>
      </div>
      @endif
    </div>
    <a href="{{ url('/') }}" class="btn">Buka Portal PKL →</a>
  </div>
  <div class="ftr">
    Email ini dikirim otomatis oleh sistem. Jangan balas email ini.<br>
    © 2025 PT Angkasa Pura Indonesia (InJourney Airports). All rights reserved.
  </div>
</div>
</body>
</html>
