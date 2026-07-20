<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class IdCardGeneratorController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('id-card-generator', $request);
    }
}
