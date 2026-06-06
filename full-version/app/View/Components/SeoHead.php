<?php
namespace App\View\Components;

use App\Services\Core\SeoService;
use Illuminate\View\Component;

class SeoHead extends Component
{
    public array $seo;

    public function __construct(array $data = [])
    {
        $this->seo = app(SeoService::class)->build($data);
    }

    public function render()
    {
        return view('components.seo-head');
    }
}
