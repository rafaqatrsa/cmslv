<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ConferenceController extends BaseUserController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('conferences', $request);
    }
}
