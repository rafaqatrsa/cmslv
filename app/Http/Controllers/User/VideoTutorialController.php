<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoTutorialController extends BaseUserController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('video-tutorials', $request);
    }
}
