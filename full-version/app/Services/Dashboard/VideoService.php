<?php

namespace App\Services\Dashboard;

use App\Models\Video;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;
use Illuminate\Support\Str;

class VideoService
{
    public function __construct(
        private readonly VideoRepositoryInterface $videoRepo
    ) {}

    public function getVideos()
    {
        return $this->videoRepo->getModel()->latest()->paginate(10);
    }

    public function createVideo(array $data)
    {
        $data['slug'] = Str::slug($data['title']['ar'] ?? 'video') . '-' . uniqid();
        $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);
        
        if ($data['video_type'] === 'upload' && isset($data['video_file'])) {
            $result = FileUpload::upload($data['video_file'], ['folder_name' => 'videos']);
            $data['video_url'] = $result->path;
        }

        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        $references = $data['references'] ?? [];
        unset($data['video_file'], $data['references'], $data['is_published']);

        $video = $this->videoRepo->create($data);
        
        $this->syncReferences($video, $references);

        return $video;
    }

    public function updateVideo(Video $video, array $data)
    {
        $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);
        
        if ($data['video_type'] === 'upload' && isset($data['video_file'])) {
            if ($video->video_type === 'upload' && $video->video_url) {
                try {
                    FileUpload::delete($video->video_url);
                } catch (\Exception $e) {}
            }

            $result = FileUpload::upload($data['video_file'], ['folder_name' => 'videos']);
            $data['video_url'] = $result->path;
        }

        if ($data['is_published'] && !$video->published_at) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        $references = $data['references'] ?? [];
        unset($data['video_file'], $data['references'], $data['is_published']);

        $this->videoRepo->update($video, $data);
        
        $this->syncReferences($video, $references);
        
        return $video->refresh();
    }

    public function deleteVideo(Video $video)
    {
        if ($video->video_type === 'upload' && $video->video_url) {
            try {
                FileUpload::delete($video->video_url);
            } catch (\Exception $e) {}
        }
        
        foreach ($video->references as $ref) {
            if ($ref->type === 'upload' && $ref->content) {
                try {
                    FileUpload::delete($ref->content);
                } catch (\Exception $e) {}
            }
        }
        
        return $this->videoRepo->delete($video);
    }
    
    protected function syncReferences(Video $video, array $references)
    {
        $keepIds = [];

        foreach ($references as $refData) {
            $id = $refData['id'] ?? null;
            $type = $refData['type'] ?? 'link';
            
            $content = $refData['content'] ?? null;
            
            if ($type === 'upload' && isset($refData['file'])) {
                $result = FileUpload::upload($refData['file'], ['folder_name' => 'video_references']);
                $content = $result->path;
            }

            if ($id) {
                $reference = $video->references()->find($id);
                if ($reference) {
                    if ($type === 'upload' && isset($refData['file']) && $reference->type === 'upload' && $reference->content) {
                        try {
                            FileUpload::delete($reference->content);
                        } catch (\Exception $e) {}
                    }
                    
                    if (!isset($refData['file']) && $type === 'upload') {
                        $content = $reference->content; // Keep old content if no new file uploaded
                    }

                    $reference->update([
                        'title' => $refData['title'] ?? null,
                        'type' => $type,
                        'content' => $content,
                    ]);
                    $keepIds[] = $reference->id;
                    continue;
                }
            }

            // Create new
            if ($content) {
                $newRef = $video->references()->create([
                    'title' => $refData['title'] ?? null,
                    'type' => $type,
                    'content' => $content,
                ]);
                $keepIds[] = $newRef->id;
            }
        }

        // Delete removed references
        $refsToDelete = $video->references()->whereNotIn('id', $keepIds)->get();
        foreach ($refsToDelete as $refToDelete) {
            if ($refToDelete->type === 'upload' && $refToDelete->content) {
                try {
                    FileUpload::delete($refToDelete->content);
                } catch (\Exception $e) {}
            }
            $refToDelete->delete();
        }
    }
}
