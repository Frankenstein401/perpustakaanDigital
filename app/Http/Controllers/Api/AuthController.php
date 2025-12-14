<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{

    protected AuthService $authService;
    protected OtpService $otpService;

    public function __construct(AuthService $authService, OtpService $otpService)
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'member_type' => 'nullable|string',
            'institution_name' => 'nullable|string',
            'identity_number' => 'nullable|string',
        ]);

        $result = $this->authService->register($validated);
        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $result = $this->authService->forgotPassword($request->email);
        
        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $result = $this->authService->resetPassword($request->email, $request->otp_code, $request->password);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|email',
            'otp_code' => 'required|string|size:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan',
                'data' => null
            ], 404);
        }

        $result = $this->otpService->verify($user, $request->otp_code);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan',
                'data' => null
            ], 404);
        }

        $result = $this->otpService->resend($user);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $result = $this->authService->login(
            $request->identifier,
            $request->password
        );

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function logout()
    {
        $result = $this->authService->logout();
        return response()->json($result, 200);
    }

    public function refresh()
    {
        $result = $this->authService->refresh();
        return response()->json($result, 200);
    }

    public function me()
    {
        $result = $this->authService->me();
        return response()->json($result, 200);
    }
}
