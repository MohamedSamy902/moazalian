<?php

namespace App\Services\Dashboard;

use App\Models\Video;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Services\Core\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;

class VideoService
{
    public function __construct(
        private readonly VideoRepositoryInterface $videoRepo,
        private readonly ImageService $imageService,
    ) {}

    /**
     * Get paginated videos with optional search/category filters and eager-loaded references.
     */
    public function getVideos(array $filters = [])
    {
        $query = Video::with('references')->withCount('references');

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['status'])) {
            match ($filters['status']) {
                'published' => $query->whereNotNull('published_at'),
                'draft'     => $query->whereNull('published_at'),
                default     => null,
            };
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    /**
     * Create a video and sync its references inside a single DB transaction.
     */
    public function createVideo(array $data): Video
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = Str::slug($data['title']['ar'] ?? 'video') . '-' . uniqid();
            $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

            if ($data['video_type'] === 'upload' && isset($data['video_file'])) {
                $result          = FileUpload::upload($data['video_file'], ['folder_name' => 'videos']);
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
        });
    }

    /**
     * Update a video and sync its references inside a single DB transaction.
     */
    public function updateVideo(Video $video, array $data): Video
    {
        return DB::transaction(function () use ($video, $data) {
            $data['is_published'] = filter_var($data['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

            if ($data['video_type'] === 'upload' && isset($data['video_file'])) {
                if ($video->video_type === 'upload' && $video->video_url) {
                    try { FileUpload::delete($video->video_url); } catch (\Exception) {}
                }
                $result          = FileUpload::upload($data['video_file'], ['folder_name' => 'videos']);
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
        });
    }

    /**
     * Delete a video and its files inside a DB transaction.
     */
    public function deleteVideo(Video $video): bool
    {
        return DB::transaction(function () use ($video) {
            if ($video->video_type === 'upload' && $video->video_url) {
                try { FileUpload::delete($video->video_url); } catch (\Exception) {}
            }

            foreach ($video->references as $ref) {
                if ($ref->type === 'upload' && $ref->content) {
                    try { FileUpload::delete($ref->content); } catch (\Exception) {}
                }
            }

            return $this->videoRepo->delete($video);
        });
    }

    /**
     * Sync video references: update existing, create new, delete removed.
     */
    protected function syncReferences(Video $video, array $references): void
    {
        $keepIds = [];

        foreach ($references as $refData) {
            $id      = $refData['id'] ?? null;
            $type    = $refData['type'] ?? 'link';
            $content = $refData['content'] ?? null;

            if ($type === 'upload' && isset($refData['file'])) {
                $result  = FileUpload::upload($refData['file'], ['folder_name' => 'video_references']);
                $content = $result->path;
            }

            if ($id) {
                $reference = $video->references()->find($id);
                if ($reference) {
                    if ($type === 'upload' && isset($refData['file']) && $reference->type === 'upload' && $reference->content) {
                        try { FileUpload::delete($reference->content); } catch (\Exception) {}
                    }
                    if (!isset($refData['file']) && $type === 'upload') {
                        $content = $reference->content;
                    }
                    $reference->update([
                        'title'   => $refData['title'] ?? null,
                        'type'    => $type,
                        'content' => $content,
                    ]);
                    $keepIds[] = $reference->id;
                    continue;
                }
            }

            if ($content) {
                $newRef    = $video->references()->create([
                    'title'   => $refData['title'] ?? null,
                    'type'    => $type,
                    'content' => $content,
                ]);
                $keepIds[] = $newRef->id;
            }
        }

        // Delete removed references
        $video->references()->whereNotIn('id', $keepIds)->get()
            ->each(function ($ref) {
                if ($ref->type === 'upload' && $ref->content) {
                    try { FileUpload::delete($ref->content); } catch (\Exception) {}
                }
                $ref->delete();
            });
    }
}
