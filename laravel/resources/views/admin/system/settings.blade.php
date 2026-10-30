@extends('layouts.admin')
@section('title','Site settings')
@section('content')
<header class="page-heading"><div><p class="eyebrow">System</p><h1>Site settings</h1><p class="muted">Update the business details used across the public website and enquiry notifications.</p></div></header>
<form method="post" action="{{ route('admin.settings.update') }}" class="panel editor-form">@csrf @method('PUT')
<div class="settings-grid">
<div class="field"><label for="business-name">Business name</label><input id="business-name" name="business_name" value="{{ old('business_name',$fields['business_name']??'') }}" required maxlength="120">@error('business_name')<span class="field-error">{{ $message }}</span>@enderror</div>
<div class="field"><label for="tagline">Site tagline</label><input id="tagline" name="tagline" value="{{ old('tagline',$fields['tagline']??'Engineering the foundations of progress') }}" maxlength="180"></div>
<div class="field"><label for="public-email">Public email</label><input id="public-email" name="public_email" type="email" value="{{ old('public_email',$fields['public_email']??'') }}" required>@error('public_email')<span class="field-error">{{ $message }}</span>@enderror</div>
<div class="field"><label for="notification-email">Enquiry notification email</label><input id="notification-email" name="notification_email" type="email" value="{{ old('notification_email',$fields['notification_email']??$fields['public_email']??'') }}"><small>Contact form notifications go here.</small>@error('notification_email')<span class="field-error">{{ $message }}</span>@enderror</div>
<div class="field"><label for="phone-numbers">Phone numbers</label><textarea id="phone-numbers" name="phone_numbers_text" rows="3" required>{{ old('phone_numbers_text',$phoneNumbersText) }}</textarea><small>Enter one number per line.</small></div>
<div class="field"><label for="whatsapp-number">WhatsApp number</label><input id="whatsapp-number" name="whatsapp_number" value="{{ old('whatsapp_number',$fields['whatsapp_number']??'') }}"></div>
<div class="field field-wide"><label for="address">Street address</label><textarea id="address" name="address" rows="2" required>{{ old('address',$fields['address']??'') }}</textarea></div>
<div class="field"><label for="po-box">P.O. Box</label><input id="po-box" name="po_box" value="{{ old('po_box',$fields['po_box']??'') }}"></div>
<div class="field"><label for="city">City</label><input id="city" name="city" value="{{ old('city',$fields['city']??'') }}" required></div>
<div class="field"><label for="country">Country</label><input id="country" name="country" value="{{ old('country',$fields['country']??'') }}" required></div>
<div class="field"><label for="opening-hours">Opening hours</label><textarea id="opening-hours" name="opening_hours_text" rows="3">{{ old('opening_hours_text',$openingHoursText) }}</textarea><small>One line per day or schedule.</small></div>
<fieldset class="field field-wide social-fields"><legend>Social links</legend><div class="form-grid">@foreach(['facebook'=>'Facebook','instagram'=>'Instagram','linkedin'=>'LinkedIn','youtube'=>'YouTube','x'=>'X'] as $key=>$label)<div class="field"><label for="social-{{ $key }}">{{ $label }}</label><input id="social-{{ $key }}" name="social_links[{{ $key }}]" type="url" value="{{ old('social_links.'.$key,$socialLinks[$key]??'') }}" placeholder="https://"></div>@endforeach</div></fieldset>
<div class="field field-wide"><span class="field-label">Content language</span><label class="language-pill"><input type="checkbox" checked disabled> English</label><small>English is the launch language. Additional languages can be enabled as translations are added.</small></div>
</div>
<div class="form-actions"><button type="submit" class="button button-primary">Save settings</button></div>
</form>
@endsection