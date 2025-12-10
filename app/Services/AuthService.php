<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function login(string $identifier, string $password)
    {
        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email atau username tidak ditemukan.',
                'data' => null
            ];
        }

        if (!Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Password salah.',
                'data' => null
            ];
        }

        // Cek status akun
        if (!$user->isActive()) {
            return [
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Hubungi admin.',
                'data' => null
            ];
        }

        if (!$user->isVerified()) {
            return [
                'success' => false,
                'message' => 'Akun ada belum di verifikasi nih, segera verifikasi',
                'data' => null
            ];
        }

        $token = JWTAuth::fromUser($user);

        return [
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60, // dalam detik
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'phone_number' => $user->phone_number,
                    'profile_picture_url' => $user->profile_picture_url,
                    'verification_status' => $user->verification_status,
                ]
            ]
        ];
    }
}
