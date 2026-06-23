<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Enums\ArticleCategory;
use App\Support\CacheKeys;
use App\Traits\ChecksPermissions;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    use ChecksPermissions, ApiResponse;

    // ──────────────────────────────────────────────
    // INDEX — paginated list with search
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $this->checkPermission('articles.view');

        $query = Article::withTrashed(false)->latest('published_at');

        if ($search = trim($request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($type = $request->get('article_type')) {
            $query->where('article_type', $type);
        }

        $articles   = $query->paginate(15)->withQueryString();
        $categories = ArticleCategory::cases();

        return view('dashboard.articles.index', compact('articles', 'categories'));
    }

    // ──────────────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────────────
    public function create()
    {
        $this->checkPermission('articles.create');
        $categories = ArticleCategory::cases();
        return view('dashboard.articles.form-modal', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('articles.create');

        $data = $request->validate([
            'title.ar'         => 'required|string|max:500',
            'title.en'         => 'nullable|string|max:500',
            'excerpt.ar'       => 'nullable|string|max:1000',
            'excerpt.en'       => 'nullable|string|max:1000',
            'body.ar'          => 'nullable|string',
            'body.en'          => 'nullable|string',
            'category'         => 'required|string',
            'article_type'     => 'required|in:html,pdf',
            'read_time'        => 'nullable|integer|min:1|max:999',
            'is_published'     => 'nullable|boolean',
            'thumbnail'        => 'nullable|image|max:5120',
            'pdf_file'         => 'nullable|file|mimes:pdf|max:51200',
            // SEO
            'seo_title.ar'        => 'nullable|string|max:255',
            'seo_title.en'        => 'nullable|string|max:255',
            'seo_description.ar'  => 'nullable|string|max:500',
            'seo_description.en'  => 'nullable|string|max:500',
            'seo_keywords.ar'     => 'nullable|string|max:500',
            'seo_keywords.en'     => 'nullable|string|max:500',
        ]);

        // Handle file uploads
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles/thumbnails', 'public');
        }
        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')->store('articles/pdfs', 'public');
        }

        // Slug
        $data['slug'] = Str::slug($data['title']['ar'] ?? 'article') . '-' . uniqid();

        // Published
        $published = filter_var($data['is_published'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data['published_at'] = $published ? now() : null;
        unset($data['is_published']);

        Article::create($data);

        Cache::forget(CacheKeys::HOME_DATA);

        if ($request->expectsJson()) {
            return $this->successResponse(__('Added successfully'));
        }
        return redirect()->route('admin.articles.index')->with('success', 'تم إضافة المقال بنجاح');
    }

    // ──────────────────────────────────────────────
    // EDIT / UPDATE
    // ──────────────────────────────────────────────
    public function edit(Article $article)
    {
        $this->checkPermission('articles.edit');
        $categories = ArticleCategory::cases();
        return view('dashboard.articles.form-modal', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $this->checkPermission('articles.edit');

        $data = $request->validate([
            'title.ar'         => 'required|string|max:500',
            'title.en'         => 'nullable|string|max:500',
            'excerpt.ar'       => 'nullable|string|max:1000',
            'excerpt.en'       => 'nullable|string|max:1000',
            'body.ar'          => 'nullable|string',
            'body.en'          => 'nullable|string',
            'category'         => 'required|string',
            'article_type'     => 'required|in:html,pdf',
            'read_time'        => 'nullable|integer|min:1|max:999',
            'is_published'     => 'nullable|boolean',
            'thumbnail'        => 'nullable|image|max:5120',
            'pdf_file'         => 'nullable|file|mimes:pdf|max:51200',
            // SEO
            'seo_title.ar'        => 'nullable|string|max:255',
            'seo_title.en'        => 'nullable|string|max:255',
            'seo_description.ar'  => 'nullable|string|max:500',
            'seo_description.en'  => 'nullable|string|max:500',
            'seo_keywords.ar'     => 'nullable|string|max:500',
            'seo_keywords.en'     => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if local
            if ($article->thumbnail && !str_starts_with($article->thumbnail, 'http')) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('articles/thumbnails', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            if ($article->pdf_path) {
                Storage::disk('public')->delete($article->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf_file')->store('articles/pdfs', 'public');
        }

        // Published status
        $published = filter_var($data['is_published'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if ($published && !$article->published_at) {
            $data['published_at'] = now();
        } elseif (!$published) {
            $data['published_at'] = null;
        }
        unset($data['is_published']);

        $article->update($data);

        Cache::forget(CacheKeys::HOME_DATA);

        if ($request->expectsJson()) {
            return $this->successResponse(__('Updated successfully'));
        }
        return redirect()->route('admin.articles.index')->with('success', 'تم تحديث المقال');
    }

    // ──────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────
    public function destroy(Article $article)
    {
        $this->checkPermission('articles.delete');

        // Delete associated files
        if ($article->thumbnail && !str_starts_with($article->thumbnail, 'http')) {
            Storage::disk('public')->delete($article->thumbnail);
        }
        if ($article->pdf_path) {
            Storage::disk('public')->delete($article->pdf_path);
        }

        $article->delete();
        Cache::forget(CacheKeys::HOME_DATA);

        return $this->successResponse(__('Deleted successfully'));
    }

    // ──────────────────────────────────────────────
    // TOGGLE PUBLISHED (AJAX)
    // ──────────────────────────────────────────────
    public function togglePublished(Article $article)
    {
        $this->checkPermission('articles.edit');

        if ($article->published_at) {
            $article->update(['published_at' => null]);
            $message = 'تم إخفاء المقال';
        } else {
            $article->update(['published_at' => now()]);
            $message = 'تم نشر المقال';
        }

        Cache::forget(CacheKeys::HOME_DATA);

        return response()->json([
            'success'      => true,
            'is_published' => $article->fresh()->is_published,
            'message'      => $message,
        ]);
    }
}
