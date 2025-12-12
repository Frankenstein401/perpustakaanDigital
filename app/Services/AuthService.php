<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserProfile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthService
{

    public function register(array $data)
    {
        if (User::where('email', $data['email'])->exists()) {
            return [
                'success' => false,
                'message' => 'Email sudah terdaftar',
                'data' => null
            ];
        }

        if (User::where('username', $data['username'])->exists()) {
            return [
                'success' => false,
                'message' => 'Username ini ada silahkan coba yang lain',
                'data' => null
            ];
        }

        try {
            $user = DB::Transaction(function () use ($data) {
                $user = User::create([
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'verification_status' => 'unverified'
                ]);
                UserProfile::create([
                    'user_id' => $user->id,
                    'full_name' => $data['full_name'],
                    'phone_number' => $data['phone_number'],
                    'member_type' => $data['member_type'] ?? 'public',
                    'address' => $data['address'],
                    'institution_name' => $data['institution_name'] ?? null,
                    'identity_number' => $data['identity_number'] ?? null
                ]);

                $roleMember = Role::where('name', 'member')->first();

                if ($roleMember) {
                    $user->roles()->attach($roleMember->id, [
                        'model_type' => 'App\Models\User'
                    ]);
                }

                // Return $user agar bisa diakses di luar transaction
                return $user;
            });

            $otpService = new OtpService();
            $otpResult = $otpService->generateAndSend($user, 'email_verification');

            if (!$otpResult['success']) {
                Log::error('Failed to send OTP code: ' . $otpResult['message']);
            }

            return [
                'success' => true,
                'message' => 'Registasi berhasil, Silahkan cek email untuk verifikasi',
                'data' => [
                    'user' => [
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'verification_status' => $user->verification_status
                    ]
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Register gagal' . $e->getMessage()
            ];
        }
    }

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

        $user->load('profile', 'roles');

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
                    'verification_status' => $user->verification_status,

                    'full_name' => $user->profile->full_name ?? null,
                    'phone_number' => $user->profile->phone_number ?? null,
                    'profile_picture_url' => $user->profile->profile_picture_url ?? null,
                    'member_type' => $user->profile->member_type ?? null,

                    'roles' => $user->roles->pluck('name')
                ]
            ]
        ];
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return [
            'success' => true,
            'message' => 'Logout berhasil',
            'data' => null
        ];
    }

    public function refresh()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());

        return [
            'success' => true,
            'message' => 'Token berhasil di refresh silahkan verifikasi',
            'data' => [
                'token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60
            ]
        ];
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->load('profile', 'roles');

        return [
            'success' => true,
            'message' => 'Data berhasil di ambil',
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'verification_status' => $user->verification_status,
                'full_name' => $user->profile->full_name ?? null,
                'phone_number' => $user->profile->phone_number ?? null,
                'profile_picture' => $user->profile->profile_picture ?? null,
                'member_type' => $user->profile->member_type ?? null,
                'roles' => $user->roles->pluck('name')
            ]
        ];
    }
}
