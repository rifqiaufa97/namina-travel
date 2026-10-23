<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationRequest $request)
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return $request->wantsJson()
                    ? response()->json(['message' => 'Email sudah terverifikasi.'], 200)
                    : redirect()->intended(route('home').'?verified=1');
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new \Illuminate\Auth\Events\Verified($request->user()));
            }

            return $request->wantsJson()
                ? response()->json([
                    'success' => true,
                    'message' => 'Email berhasil diverifikasi!',
                    'redirect' => route('home').'?verified=1'
                ])
                : redirect()->intended(route('home').'?verified=1');

        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            
            return $request->wantsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memverifikasi email.'
                ], 500)
                : back()->with('error', 'Terjadi kesalahan saat memverifikasi email.');
        }
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return $request->wantsJson()
                    ? response()->json(['message' => 'Email sudah terverifikasi.'], 200)
                    : back()->with('status', 'Email sudah terverifikasi.');
            }

            $request->user()->sendEmailVerificationNotification();

            return $request->wantsJson()
                ? response()->json([
                    'success' => true,
                    'message' => 'Tautan verifikasi baru telah dikirim ke alamat email Anda.'
                ])
                : back()->with('status', 'Tautan verifikasi baru telah dikirim ke alamat email Anda.');

        } catch (\Exception $e) {
            Log::error('Resend verification email error: ' . $e->getMessage());
            
            return $request->wantsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim ulang email verifikasi. Silakan coba lagi nanti.'
                ], 500)
                : back()->with('error', 'Gagal mengirim ulang email verifikasi. Silakan coba lagi nanti.');
        }
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Email sudah terverifikasi.'], 200)
                : redirect()->intended(route('home'));
        }

        return $request->wantsJson()
            ? response()->json([
                'verified' => false,
                'message' => 'Silakan verifikasi alamat email Anda.'
            ], 403)
            : view('auth.verify-email');
    }
}
