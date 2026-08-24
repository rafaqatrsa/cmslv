<?php

namespace App\Http\Controllers\Admin\Academics;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GroomingDomainController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('grooming-domains', $request);
    }
}
