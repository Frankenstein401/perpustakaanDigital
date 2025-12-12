<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpLog extends Model
{
    //
    use HasFactory; 

    protected $table = 'otp_logs';

    protected $fillable = [
        'user_id',
        'otp_code_hash',
        'channel',
        'purpose',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
