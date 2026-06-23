<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\NewsletterRequest;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function store(NewsletterRequest $request)
    {
        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->validated('email')]
        );

        return response()->json([
            'success' => true,
            'message' => 'شكراً! تم تسجيل بريدك الإلكتروني بنجاح.',
        ]);
    }
}
