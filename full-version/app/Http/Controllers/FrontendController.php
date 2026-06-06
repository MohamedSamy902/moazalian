<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Section;

class FrontendController extends Controller
{
    public function index()
    {
        $sections = Section::where('page', 'home')->get()->keyBy('key');
        return view('front.index', compact('sections'));
    }
}
