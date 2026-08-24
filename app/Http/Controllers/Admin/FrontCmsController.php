<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class FrontCmsController extends Controller
{
    public function index(): View
    {
        $pages = Page::query()->latest('created_at')->limit(10)->get();
        $posts = Post::query()->latest('created_at')->limit(10)->get();
        $mediaCount = Gallery::query()->count();

        return view('admin.frontcms.index', compact('pages', 'posts', 'mediaCount'));
    }
}
