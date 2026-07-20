<?php

namespace App\Http\Controllers\Biometric;

use App\Http\Controllers\Controller;
use App\Services\Biometric\BiometricUserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BiometricController extends Controller
{
    public function __construct(private readonly BiometricUserService $users) {}

    public function index(Request $request): JsonResponse
    {
        abort_unless($this->users->isAuthorized($request), 403);

        return response()->json([
            'status' => 'ok',
            'message' => 'Biometric endpoint is available.',
        ]);
    }

    public function getUser(Request $request): JsonResponse
    {
        abort_unless($this->users->isAuthorized($request), 403);

        return response()->json([
            'users' => $this->users->users(),
        ]);
    }
}
