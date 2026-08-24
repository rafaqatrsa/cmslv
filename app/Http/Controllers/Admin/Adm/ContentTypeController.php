<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentTypeController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('content-types', $request);
    }
}
