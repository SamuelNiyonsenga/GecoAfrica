<?php

namespace App\Http\Controllers;

use App\Mail\ContactEnquiryReceived;
use App\Models\ContactMessage;
use App\Models\CmsSiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact', [
            'pageTitle' => 'Contact GECO Africa | Engineering & Project Enquiries in Rwanda',
            'pageDescription' => 'Contact GECO Africa in Kigali about geotechnical investigations, laboratory testing, engineering design and project supervision. Call, email or send a project enquiry.',
            'canonicalUrl' => 'https://gecoafrica.com/contact/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'ogImageAlt' => 'GECO Africa geotechnical fieldwork in Rwanda',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'ContactPage',
                'name' => 'Contact GECO Africa',
                'url' => 'https://gecoafrica.com/contact/',
                'description' => 'Contact GECO Africa Ltd in Kigali, Rwanda.',
                'mainEntity' => [
                    '@id' => 'https://gecoafrica.com/#organization',
                    '@type' => 'Organization',
                    'name' => 'GECO Africa Ltd',
                    'url' => 'https://gecoafrica.com/',
                    'email' => 'info@gecoafrica.com',
                    'telephone' => ['+250 788 842 990', '+250 788 572 346'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => 'Avenue des Poids Lourds, Source Oil Petrol Station building',
                        'postOfficeBoxNumber' => '3711',
                        'addressLocality' => 'Kigali',
                        'addressCountry' => 'RW',
                    ],
                ],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('company_website')) {
            return to_route('contact')->withErrors(['contact' => 'We could not send that enquiry. Please try again.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'service' => ['nullable', 'in:general,geotechnical,laboratory,roads,design,other'],
            'message' => ['required', 'string', 'max:5000'],
            'company_website' => ['nullable', 'string', 'max:255'],
        ]);

        $serviceLabels = [
            'general' => 'General enquiry',
            'geotechnical' => 'Geotechnical investigation',
            'laboratory' => 'Laboratory testing',
            'roads' => 'Roads and transport',
            'design' => 'Engineering design and supervision',
            'other' => 'Other project or service',
        ];

        $enquiry = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service' => $serviceLabels[$validated['service'] ?? 'general'],
            'message' => $validated['message'],
        ]);

        try {
            $siteSettings = CmsSiteSetting::query()->find(1)?->fields ?? [];
            Mail::to($siteSettings['notification_email'] ?? config('contact.recipient'))->send(new ContactEnquiryReceived($enquiry));
        } catch (Throwable $exception) {
            report($exception);
        }

        return to_route('contact')->with('contact_success', 'Thank you. Your enquiry has been received. The GECO Africa team will be in touch.');
    }
}
