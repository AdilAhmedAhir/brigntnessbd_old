<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class ContactController extends Controller
{
    public function index()
    {
        $contactSettings = [
            'contact_address' => Setting::get('footer_address', ''),
            'contact_phone' => Setting::get('footer_phone', ''),
            'contact_email' => Setting::get('footer_email', ''),
            'contact_hours' => Setting::get('footer_hours', ''),
            'contact_map_url' => Setting::get('contact_map_url', ''),
            'contact_description' => Setting::get('contact_description', ''),
        ];
        
        return view('website.contact', compact('contactSettings'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $adminEmail = Setting::get('contact_admin_email', Setting::get('footer_email', 'admin@example.com'));
        
        try {
            // Send email to admin
            Mail::send('emails.contact', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'messageContent' => $request->message,
            ], function ($message) use ($request, $adminEmail) {
                $message->to($adminEmail)
                        ->subject('New Contact Form Message: ' . $request->subject)
                        ->replyTo($request->email, $request->name);
            });

            return redirect()->back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
}
