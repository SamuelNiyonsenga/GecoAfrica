@extends('layouts.site')

@section('content')
<main id="main-content" class="contact-page">
    <section class="contact-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Contact</span>
            </nav>

            <div class="contact-hero-grid">
                <div class="contact-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> Kigali · Rwanda · East Africa</p>
                    <h1>Let’s talk about what <em>you’re building.</em></h1>
                    <p>Share a project brief, ask about our services or reach the GECO Africa team directly. We’ll help you find the right next step.</p>
                    <a class="button button-dark" href="#contact-form">Send a project enquiry <span aria-hidden="true">↓</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-main-section" id="contact-form">
        <div class="site-container">
            <div class="contact-main-grid">
                <section class="contact-form-card" aria-labelledby="contact-form-title">
                    <div class="contact-form-heading">
                        <p class="eyebrow"><span class="eyebrow-line"></span> Start a conversation</p>
                        <h2 id="contact-form-title">Tell us about your <em>project.</em></h2>
                        <p>Leave a few details and the GECO Africa team will follow up with you.</p>
                    </div>

                    @if (session('contact_success'))
                        <div class="contact-success-panel" role="status" tabindex="-1">
                            <p class="eyebrow"><span class="eyebrow-line"></span> Enquiry received</p>
                            <h3>Thank you for getting in touch.</h3>
                            <p>{{ session('contact_success') }}</p>
                            <a class="text-link" href="{{ route('home') }}">Return to the homepage <span aria-hidden="true">→</span></a>
                        </div>
                    @else
                    @if ($errors->any())
                        <div class="contact-alert contact-alert-error" role="alert" tabindex="-1">
                            <strong>Please review the highlighted fields.</strong>
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="contact-honeypot" aria-hidden="true">
                            <label for="company_website">Leave this field empty</label>
                            <input id="company_website" name="company_website" type="text" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="contact-field-row">
                            <div class="contact-field">
                                <label for="contact-name">Your name <span aria-hidden="true">*</span></label>
                                <input id="contact-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="150" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                                @error('name')<span class="contact-field-error" id="name-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="contact-field">
                                <label for="contact-email">Email address <span aria-hidden="true">*</span></label>
                                <input id="contact-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                @error('email')<span class="contact-field-error" id="email-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="contact-field-row">
                            <div class="contact-field">
                                <label for="contact-phone">Phone number <span aria-hidden="true">*</span></label>
                                <input id="contact-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" maxlength="40" required @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                                @error('phone')<span class="contact-field-error" id="phone-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="contact-field">
                                <label for="contact-service">What can we help with?</label>
                                <select id="contact-service" name="service" @error('service') aria-invalid="true" aria-describedby="service-error" @enderror>
                                    <option value="">Choose a service (optional)</option>
                                    <option value="general" @selected(old('service') === 'general')>General enquiry</option>
                                    <option value="geotechnical" @selected(old('service') === 'geotechnical')>Geotechnical investigation</option>
                                    <option value="laboratory" @selected(old('service') === 'laboratory')>Laboratory testing</option>
                                    <option value="roads" @selected(old('service') === 'roads')>Roads and transport</option>
                                    <option value="design" @selected(old('service') === 'design')>Engineering design and supervision</option>
                                    <option value="other" @selected(old('service') === 'other')>Other project or service</option>
                                </select>
                                @error('service')<span class="contact-field-error" id="service-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="contact-field">
                            <label for="contact-message">How can we help? <span aria-hidden="true">*</span></label>
                            <textarea id="contact-message" name="message" rows="6" maxlength="5000" required @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                            @error('message')<span class="contact-field-error" id="message-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="contact-submit-row">
                            <p>Your contact details will be used to respond to this enquiry.</p>
                            <button class="button button-dark" type="submit">Send enquiry <span aria-hidden="true">↗</span></button>
                        </div>
                    </form>
                    @endif
                </section>

                <aside class="contact-details-column">
                    <section class="contact-details-card" aria-labelledby="contact-details-heading">
                        <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Contact information</p>
                        <h2 id="contact-details-heading">Reach us <em>directly.</em></h2>

                        <div class="contact-detail-item">
                            <span class="contact-detail-icon" aria-hidden="true">01</span>
                            <div><small>Call our office</small><a href="tel:+250788842990">+250 788 842 990</a><a href="tel:+250788572346">+250 788 572 346</a></div>
                        </div>
                        <div class="contact-detail-item">
                            <span class="contact-detail-icon" aria-hidden="true">02</span>
                            <div><small>Email</small><a href="mailto:info@gecoafrica.com">info@gecoafrica.com</a></div>
                        </div>
                        <div class="contact-detail-item">
                            <span class="contact-detail-icon" aria-hidden="true">03</span>
                            <div><small>Office</small><p>Avenue des Poids Lourds,<br>Source Oil Petrol Station building,<br>Kigali, Rwanda</p></div>
                        </div>
                        <div class="contact-detail-item">
                            <span class="contact-detail-icon" aria-hidden="true">04</span>
                            <div><small>Postal address</small><p>P.O. Box 3711, Kigali</p></div>
                        </div>

                        <a class="contact-website-link" href="https://gecoafrica.com/" target="_blank" rel="noopener noreferrer">gecoafrica.com <span aria-hidden="true">↗</span></a>
                    </section>

                    <section class="contact-map-card" aria-label="Find the GECO Africa office">
                        <div class="contact-map-heading"><div><span>Find our office</span><strong>Kigali, Rwanda</strong></div><span aria-hidden="true">↗</span></div>
                        <div class="contact-map-slot" data-contact-map-slot><p>Avenue des Poids Lourds, Kigali, Rwanda</p><button type="button" data-contact-load-map>Load interactive map</button></div>
                        <div class="contact-map-actions">
                            <a href="https://www.google.com/maps/search/?api=1&amp;query=GECO+Africa+LTD+Kigali" target="_blank" rel="noopener noreferrer">Open in Google Maps <span aria-hidden="true">↗</span></a>
                            <a href="https://www.google.com/maps/dir/?api=1&amp;destination=GECO+Africa+LTD+Kigali" target="_blank" rel="noopener noreferrer">Get directions <span aria-hidden="true">↗</span></a>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection
