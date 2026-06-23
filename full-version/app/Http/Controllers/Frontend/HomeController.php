<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\HomeService;

class HomeController extends Controller
{
    public function __construct(
        private readonly HomeService $homeService
    ) {}

    public function index()
    {
        $data = $this->homeService->getHomeData();
        // dd($data['sections']['hero_title']->value);
        // The view will receive the cached payload
        return view('frontend.index', $data);
    }
}
