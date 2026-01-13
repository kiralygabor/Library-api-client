<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $response = Http::post('http://localhost:8000/api/users/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
			// elmentjük a bejelentkezési adatokat a session-be.         
            $user = $response['user'];
            session([
                'api_token' => $user['token'],
                //'user_name' => $user['name'],
				'user_email' => $user['email'],
            ]);

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Hibás bejelentkezési adatok.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        session()->forget('api_token');
        session()->forget('user_name');
        session()->forget('user_email');


        return redirect('/');
    }
}
