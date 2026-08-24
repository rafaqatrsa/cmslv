<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class QmsController extends Controller
{
    public function index(): View
    {
        $migrationNote = 'No QMS-specific database table or CodeIgniter controller was found in the available Laravel workspace.';

        return view('admin.qms.index', compact('migrationNote'));
    }
}
