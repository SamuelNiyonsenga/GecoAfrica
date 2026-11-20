{{-- Shared HTML email frame. Inline styles only: email apps ignore stylesheets. --}}
@php($brand = \App\Support\Seo::settings()['business_name'] ?? 'GECO Africa')
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $title ?? $brand }}</title></head>
<body style="margin:0;padding:0;background:#f5f8fc;font-family:Arial,Helvetica,sans-serif;color:#142b44;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f8fc;padding:24px 12px;">
<tr><td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border:1px solid #d9e3ee;border-radius:10px;">
<tr><td style="padding:22px 28px;border-bottom:3px solid #dd1c26;">
    <img src="{{ \App\Support\Seo::absoluteUrl('images/geco-logo.png') }}" width="150" alt="{{ $brand }}" style="display:block;border:0;max-width:150px;height:auto;">
</td></tr>
<tr><td style="padding:28px;font-size:15px;line-height:1.6;">
{!! $slot !!}
</td></tr>
<tr><td style="padding:16px 28px;border-top:1px solid #d9e3ee;font-size:12px;line-height:1.5;color:#566c81;">
    {{ $brand }} · {{ rtrim(preg_replace('#^https?://#', '', (string) config('app.url')), '/') }}
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
