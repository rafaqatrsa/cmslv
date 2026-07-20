<?php

namespace App\Http\Controllers\Admin\Front;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends BaseFrontController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('galleries', $request);
    }
}
