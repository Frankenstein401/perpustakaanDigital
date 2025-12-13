<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OtpLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    public function generateAndSend(User $user, string $purpose = 'email_verification')
    {
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otpHash = Hash::make($otpCode);

        OtpLog::create([
            'user_id' => $user->id,
            'otp_code_hash' => $otpHash,
            'channel' => 'email',
            'purpose' => $purpose,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otpCode, $user->username));

            return [
                'success' => true,
                'message' => 'Otp berhasil terkirim ke email',
                'data' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message'  => 'Otp gagal terkirim',
                'data' => null
            ];
        }
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return [
                'success' => false,
                'message' => 'Email sudah terverifikasi',
                'data' => null
            ];
        }

        $existingOtp = OtpLog::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($existingOtp) {
            $otp = OtpLog::where('user_id', $user->id)
                ->where('is_used', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            $minutesLeft = Carbon::now()->diffInMinutes($otp->expires_at);

            return [
                'success' => false,
                'message' => "OTP masih valid. Expired dalam {$minutesLeft} menit.",
            ];
        }

        return $this->generateAndSend($user, 'email_verification');
    }

    public function verify(User $user, string $otpCode)
    {
        $otpLog = OtpLog::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();


        if (!$otpLog) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
                'data' => null
            ];
        }

        if (!Hash::check($otpCode, $otpLog->otp_code_hash)) {
            return [
                'success' => false,
                'message' => 'Kode OTP yang dimasukkan salah'
            ];
        }

        $otpLog->update(['is_used', true]);

        $user->update(['verification_status', 'verified']);

        return [
            'success' => true,
            'message' => 'Verifikasi akun berhasil! Akun kamu sudah aktif'
        ];
    }
}
