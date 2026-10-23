<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return redirect()->route('home', ['auth' => 'login']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember', false);

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                
                $user = Auth::user();
                $redirectUrl = ($user && $user->isAdmin()) ? route('admin.dashboard') : route('home');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'redirect' => $redirectUrl
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return redirect()->route('home', ['auth' => 'register']);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'user',
                'email_verification_token' => Str::random(60),
            ]);

            // Log the user in
            Auth::login($user);

            // Mark email as verified (skip email verification for now)
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Anda telah masuk secara otomatis.',
                'redirect' => route('home')
            ]);

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Anda telah berhasil logout.',
                'redirect' => route('home')
            ]);
        }

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
