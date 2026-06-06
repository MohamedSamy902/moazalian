<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\QuickResponses\QuickResponseRequest;
use App\Models\QuickResponse;
use App\Services\Dashboard\QuickResponseService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;

class QuickResponseController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly QuickResponseService $responseService
    ) {}

    public function index(Request $request)
    {
        $this->checkPermission('quick-responses.view');
        $filters   = $request->only(['search', 'type']);
        $responses = $this->responseService->getResponses($filters);
        return view('dashboard.quick-responses.index', compact('responses'));
    }

    public function create()
    {
        $this->checkPermission('quick-responses.create');
        return view('dashboard.quick-responses.form-modal');
    }

    public function store(QuickResponseRequest $request)
    {
        $this->checkPermission('quick-responses.create');

        $this->responseService->createResponse($request->validated());

        return $this->successResponse(__('Added successfully'));
    }

    public function edit(QuickResponse $quickResponse)
    {
        $this->checkPermission('quick-responses.edit');
        return view('dashboard.quick-responses.form-modal', compact('quickResponse'));
    }

    public function update(QuickResponseRequest $request, QuickResponse $quickResponse)
    {
        $this->checkPermission('quick-responses.edit');

        $this->responseService->updateResponse($quickResponse, $request->validated());

        return $this->successResponse(__('Updated successfully'));
    }

    public function destroy(QuickResponse $quickResponse)
    {
        $this->checkPermission('quick-responses.delete');

        try {
            $this->responseService->deleteResponse($quickResponse);
            return $this->successResponse(__('Deleted successfully'));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
