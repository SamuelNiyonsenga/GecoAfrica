<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Support\CmsContentRepository;
use App\Support\EnquiryMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(?Request $request = null): View
    {
        $selected = (string) ($request ?? request())->query('service', '');

        return view('contact', [
            'pageTitle' => 'Contact GECO Africa | Engineering & Project Enquiries in Rwanda',
            'pageDescription' => 'Contact GECO Africa in Kigali about geotechnical investigations, laboratory testing, engineering design and project supervision. Call, email or send a project enquiry.',
            'canonicalUrl' => 'https://gecoafrica.com/contact/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'ogImageAlt' => 'GECO Africa geotechnical fieldwork in Rwanda',
            'serviceOptions' => self::serviceOptions(),
            'selectedService' => array_key_exists($selected, self::serviceOptions()) ? $selected : '',
        ]);
    }

    /** "What can we help with?" choices: the services managed in the CMS, plus general and other. */
    public static function serviceOptions(): array
    {
        $options = ['general' => 'General enquiry'];
        foreach (CmsContentRepository::services() as $service) {
            if (($service['slug'] ?? '') !== '' && ($service['title'] ?? '') !== '') {
                $options[$service['slug']] = $service['title'];
            }
        }
        $options['other'] = 'Other project or service';

        return $options;
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('company_website')) {
            return to_route('contact')->withErrors(['contact' => 'We could not send that enquiry. Please try again.']);
        }

        $options = self::serviceOptions();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'service' => ['nullable', Rule::in(array_keys($options))],
            'message' => ['required', 'string', 'max:5000'],
            'company_website' => ['nullable', 'string', 'max:255'],
        ]);

        $enquiry = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service' => $options[$validated['service'] ?? 'general'] ?? 'General enquiry',
            'message' => $validated['message'],
        ]);

        // The enquiry is saved before any email is attempted, so it is never lost. The emails are
        // sent after the visitor's page is delivered (defer), so a slow or broken mail server never
        // keeps them waiting. Each result is recorded; failures show under Enquiries with Resend.
        \Illuminate\Support\defer(fn () => EnquiryMailer::deliver($enquiry), 'enquiry-mail-'.$enquiry->id, always: true);

        return to_route('contact')->with('contact_success', 'Thank you. Your enquiry has been received. The GECO Africa team will be in touch.');
    }
}
