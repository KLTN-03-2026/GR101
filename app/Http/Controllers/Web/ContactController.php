<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('web.contact.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data = $request->only('name', 'email', 'subject', 'content');
        
        // Anti-XSS: strip tags to prevent malicious scripts
        $data['content'] = strip_tags($data['content']);
        $data['subject'] = strip_tags($data['subject']);

        $contact = Contact::create($data);

        // Send email notification to Admin
        try {
            Mail::raw("Bạn có liên hệ mới từ: {$contact->name} ({$contact->email}).\n\nChủ đề: {$contact->subject}\n\nNội dung:\n{$contact->content}", function ($message) use ($contact) {
                $message->to(env('MAIL_FROM_ADDRESS'))
                        ->subject('[' . env('APP_NAME') . '] Liên hệ mới từ khách hàng: ' . $contact->subject);
            });
        } catch (\Exception $e) {
            \Log::error('Send contact email error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }
}
