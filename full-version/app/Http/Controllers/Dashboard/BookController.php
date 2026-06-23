<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Traits\ChecksPermissions;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    use ChecksPermissions, ApiResponse;
    public function index(Request $request)
    {
        $this->checkPermission('books.view');

        $query = Book::orderByDesc('is_featured')->orderByDesc('created_at');

        if ($search = trim($request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%")
                  ->orWhere('author->ar', 'like', "%{$search}%");
            });
        }

        $books = $query->paginate(20)->withQueryString();
        return view('dashboard.books.index', compact('books'));
    }

    /**
     * Toggle is_featured via AJAX — called from the books list table.
     */
    public function toggleFeatured(Book $book)
    {
        $book->update(['is_featured' => !$book->is_featured]);

        // Clear homepage cache so it reflects immediately
        Cache::forget(\App\Support\CacheKeys::HOME_DATA);

        return response()->json([
            'success'     => true,
            'is_featured' => $book->is_featured,
            'message'     => $book->is_featured
                ? 'تم إضافة الكتاب إلى الصفحة الرئيسية'
                : 'تم إزالة الكتاب من الصفحة الرئيسية',
        ]);
    }

    public function create()
    {
        return view('dashboard.books.create');
    }

    public function store(Request $request)
    {
        // Basic store — extend as needed
        $data = $request->validate([
            'title.ar'    => 'required|string|max:300',
            'title.en'    => 'nullable|string|max:300',
            'author.ar'   => 'nullable|string|max:200',
            'author.en'   => 'nullable|string|max:200',
            'year'        => 'nullable|integer|min:1900|max:2099',
            'category'    => 'required|string',
            'is_featured' => 'boolean',
            'pdf_file'    => 'nullable|file|mimes:pdf|max:51200',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')
                ->store('books/pdfs', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        $data['title']  = ['ar' => $data['title']['ar'] ?? '', 'en' => $data['title']['en'] ?? ''];
        $data['author'] = ['ar' => $data['author']['ar'] ?? '', 'en' => $data['author']['en'] ?? ''];
        $data['is_featured'] = $request->boolean('is_featured');
        Book::create($data);

        Cache::forget(\App\Support\CacheKeys::HOME_DATA);
        return redirect()->route('admin.books.index')->with('success', 'تم إضافة الكتاب بنجاح');
    }

    public function edit(Book $book)
    {
        return view('dashboard.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title.ar'    => 'required|string|max:300',
            'title.en'    => 'nullable|string|max:300',
            'author.ar'   => 'nullable|string|max:200',
            'author.en'   => 'nullable|string|max:200',
            'year'        => 'nullable|integer|min:1900|max:2099',
            'category'    => 'required|string',
            'is_featured' => 'boolean',
            'pdf_file'    => 'nullable|file|mimes:pdf|max:51200',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')
                ->store('books/pdfs', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        $data['title']  = ['ar' => $data['title']['ar'] ?? '', 'en' => $data['title']['en'] ?? ''];
        $data['author'] = ['ar' => $data['author']['ar'] ?? '', 'en' => $data['author']['en'] ?? ''];
        $data['is_featured'] = $request->boolean('is_featured');
        $book->update($data);

        Cache::forget(\App\Support\CacheKeys::HOME_DATA);
        return redirect()->route('admin.books.index')->with('success', 'تم تحديث الكتاب');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        Cache::forget(\App\Support\CacheKeys::HOME_DATA);
        return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
    }
}
