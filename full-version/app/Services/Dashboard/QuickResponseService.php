<?php

namespace App\Services\Dashboard;

use App\Models\QuickResponse;
use App\Repositories\Interfaces\QuickResponseRepositoryInterface;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;

class QuickResponseService
{
    public function __construct(
        private readonly QuickResponseRepositoryInterface $responseRepo
    ) {}

    public function getResponses()
    {
        return $this->responseRepo->getModel()->latest()->paginate(10);
    }

    public function createResponse(array $data)
    {
        $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

        if ($data['type'] === 'image' && isset($data['image'])) {
            $result = FileUpload::upload($data['image'], ['folder_name' => 'quick-responses']);
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
    }

    public function updateResponse(QuickResponse $response, array $data)
    {
        $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

        if ($data['type'] === 'image') {
            if (isset($data['image'])) {
                if ($response->type === 'image' && $response->attachment) {
                    try {
                        FileUpload::delete($response->attachment);
                    } catch (\Exception $e) {}
                }
                $result = FileUpload::upload($data['image'], ['folder_name' => 'quick-responses']);
                $data['attachment'] = $result->path;
            } else {
                $data['attachment'] = $response->attachment;
            }
        } elseif ($data['type'] === 'video') {
            $data['attachment'] = $data['youtube_url'] ?? null;
            // Cleanup old image if changed type
            if ($response->type === 'image' && $response->attachment) {
                try {
                    FileUpload::delete($response->attachment);
                } catch (\Exception $e) {}
            }
        } else {
            $data['attachment'] = null;
            if ($response->type === 'image' && $response->attachment) {
                try {
                    FileUpload::delete($response->attachment);
                } catch (\Exception $e) {}
            }
        }

        if ($data['type'] === 'text') {
            $data['content'] = [
                'ar' => $data['content_text']['ar'] ?? '',
                'en' => $data['content_text']['en'] ?? '',
            ];
        } else {
            $data['content'] = null;
        }

        unset($data['image'], $data['youtube_url'], $data['content_text']);

        $this->responseRepo->update($response, $data);
        return $response->refresh();
    }

    public function deleteResponse(QuickResponse $response)
    {
        if ($response->type === 'image' && $response->attachment) {
            try {
                FileUpload::delete($response->attachment);
            } catch (\Exception $e) {}
        }
        
        return $this->responseRepo->delete($response);
    }
}
