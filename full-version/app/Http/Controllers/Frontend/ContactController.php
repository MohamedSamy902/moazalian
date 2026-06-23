<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(ContactRequest $request)
    {
        // TODO: Send email notification or store in DB
        // For now, just return success (can integrate a ContactMessage model later)

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.',
            ]);
        }

        return back()->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }
}
