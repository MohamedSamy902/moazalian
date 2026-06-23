<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Services\Frontend\BookService;

class BookController extends Controller
{
    public function __construct(
        private readonly BookService $bookService,
    ) {}

    public function index(SearchRequest $request)
    {
        $books = $this->bookService->getPaginated($request->validated());

        return view('frontend.books.index', compact('books'));
    }
}
