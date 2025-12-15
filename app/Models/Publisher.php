<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    //
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string'; 

    protected $fillable = [
        'name'
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'publisher_id');
    }
}
