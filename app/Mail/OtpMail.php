<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;
    public $userName;

    public function __construct($otpCode, $userName)
    {
        $this->otpCode = $otpCode;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Kode OTP Verifikasi Akun')
                    ->view('emails.otp');
    }
}