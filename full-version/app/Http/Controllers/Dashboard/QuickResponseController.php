<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\QuickResponse;
use App\Services\Dashboard\QuickResponseService;
use App\Http\Requests\Dashboard\QuickResponses\StoreQuickResponseRequest;
use App\Http\Requests\Dashboard\QuickResponses\UpdateQuickResponseRequest;

class QuickResponseController extends Controller
{
    public function __construct(
        private readonly QuickResponseService $responseService
    ) {}

    public function index()
    {
        $responses = $this->responseService->getResponses();
        return view('dashboard.quick-responses.index', compact('responses'));
    }

    public function create()
    {
        return view('dashboard.quick-responses.form-modal');
    }

    public function store(StoreQuickResponseRequest $request)
    {
        $this->responseService->createResponse($request->validated());
        return response()->json(['success' => true, 'message' => __('Added successfully')]);
    }

    public function edit(QuickResponse $quickResponse)
    {
        return view('dashboard.quick-responses.form-modal', compact('quickResponse'));
    }

    public function update(UpdateQuickResponseRequest $request, QuickResponse $quickResponse)
    {
        $this->responseService->updateResponse($quickResponse, $request->validated());
        return response()->json(['success' => true, 'message' => __('Updated successfully')]);
    }

    public function destroy(QuickResponse $quickResponse)
    {
        $this->responseService->deleteResponse($quickResponse);
        return redirect()->route('admin.quick-responses.index')->with('success', __('Deleted successfully'));
    }
}
