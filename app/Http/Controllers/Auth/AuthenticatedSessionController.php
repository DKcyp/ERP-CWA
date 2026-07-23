<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $data = [
            'app_name' => config('app.name'),
            'app_description' => '',
        ];

        $config = Configuration::get();
        foreach ($config as $conf) {
            $code = str_replace('.', '_', $conf->config_code);
            $data[$code] = $conf->config_value;
        }
        return view('auth.login', compact('data'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $logo = Configuration::where('config_code', 'app.logo')->first();
        $name = Configuration::where('config_code', 'app.name')->first();
        if ($logo) {
            Session::put('logo', asset("uploads/" . $logo->config_value));
        }
        if ($name) {
            Session::put('app_name', asset("uploads/" . $name->config_value));
        }
        $request->session()->regenerate();

        addLogUser('Login');
        // if (auth()->user()->user_type == 'mahasiswa') {
        //     return redirect()->route('mahasiswa.dashboard');
        // }

        // if(auth()->user()->usertype != 'superadmin'){
        //     return redirect()->route('mahasiswa.dashboard');
        // }
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        addLogUser('Logout');
        Auth::guard('web')->logout();


        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
