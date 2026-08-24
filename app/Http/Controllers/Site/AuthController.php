<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSigninRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the site sign-in form or process a site sign-in submission.
     */
    public function signin(SiteSigninRequest $request): RedirectResponse|View
    {
        if ($request->isMethod('get')) {
            return view('site.auth.signin', [
                'signinRouteName' => $request->is('site/signin') ? 'site.signin.legacy' : 'site.signin',
            ]);
        }

        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard', absolute: false));
    }

    /**
     * End the authenticated site session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('site')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('site.signin');
    }
}
