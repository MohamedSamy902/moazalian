<?php

namespace App\Services\Dashboard;

use App\Models\QuickResponse;
use App\Repositories\Interfaces\QuickResponseRepositoryInterface;
use App\Services\Core\ImageService;
use Illuminate\Support\Facades\DB;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;

class QuickResponseService
{
    public function __construct(
        private readonly QuickResponseRepositoryInterface $responseRepo,
        private readonly ImageService $imageService,
    ) {}

    /**
     * Get paginated quick responses with optional search/type filters.
     */
    public function getResponses(array $filters = [])
    {
        $query = $this->responseRepo->getModel()->latest();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->paginate(10)->withQueryString();
    }

    public function createResponse(array $data): QuickResponse
    {
        return DB::transaction(function () use ($data) {
            $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

            if ($data['type'] === 'image' && isset($data['image'])) {
                $this->imageService->compress($data['image']);
                $result           = FileUpload::upload($data['image'], ['folder_name' => 'quick-responses']);
                $data['attachment'] = $result->path;
            } elseif ($data['type'] === 'video') {
                $data['attachment'] = $data['youtube_url'] ?? null;
            }

            if ($data['type'] === 'text') {
                $data['content'] = [
                    'ar' => $data['content_text']['ar'] ?? '',
                    'en' => $data['content_text']['en'] ?? '',
                ];
            }

            unset($data['image'], $data['youtube_url'], $data['content_text']);

            return $this->responseRepo->create($data);
        });
    }

    public function updateResponse(QuickResponse $response, array $data): QuickResponse
    {
        return DB::transaction(function () use ($response, $data) {
            $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

            if ($data['type'] === 'image') {
                if (isset($data['image'])) {
                    if ($response->type === 'image' && $response->attachment) {
                        try { FileUpload::delete($response->attachment); } catch (\Exception) {}
                    }
                    $this->imageService->compress($data['image']);
                    $result           = FileUpload::upload($data['image'], ['folder_name' => 'quick-responses']);
                    $data['attachment'] = $result->path;
                } else {
                    $data['attachment'] = $response->attachment;
                }
            } elseif ($data['type'] === 'video') {
                $data['attachment'] = $data['youtube_url'] ?? null;
                if ($response->type === 'image' && $response->attachment) {
                    try { FileUpload::delete($response->attachment); } catch (\Exception) {}
                }
            } else {
                $data['attachment'] = null;
                if ($response->type === 'image' && $response->attachment) {
                    try { FileUpload::delete($response->attachment); } catch (\Exception) {}
                }
            }

            $data['content'] = $data['type'] === 'text'
                ? ['ar' => $data['content_text']['ar'] ?? '', 'en' => $data['content_text']['en'] ?? '']
                : null;

            unset($data['image'], $data['youtube_url'], $data['content_text']);

            $this->responseRepo->update($response, $data);
            return $response->refresh();
        });
    }

    public function deleteResponse(QuickResponse $response): bool
    {
        return DB::transaction(function () use ($response) {
            if ($response->type === 'image' && $response->attachment) {
                try { FileUpload::delete($response->attachment); } catch (\Exception) {}
            }
            return $this->responseRepo->delete($response);
        });
    }
}
