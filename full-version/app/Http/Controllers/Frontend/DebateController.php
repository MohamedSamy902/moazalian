<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Models\Debate;
use App\Services\Frontend\DebateService;

class DebateController extends Controller
{
    public function __construct(
        private readonly DebateService $debateService,
    ) {}

    public function index(SearchRequest $request)
    {
        $debates = $this->debateService->getPaginated($request->validated());

        return view('frontend.debates.index', compact('debates'));
    }

    public function show(int $id)
    {
        $debate = Debate::findOrFail($id);

        return view('frontend.debates.show', compact('debate'));
    }
}
