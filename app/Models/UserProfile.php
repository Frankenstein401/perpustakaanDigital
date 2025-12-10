<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    use HasFactory, HasUuids;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'users_profiles';

    protected $fillable = [
        'full_name',
        'phone_number',
        'address',
        'gender',
        'profile_picture',
        'member_type',
        'institution_name',
        'identity_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
