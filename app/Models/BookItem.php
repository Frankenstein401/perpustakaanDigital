<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    //
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected  $table = 'book_items';

    protected $fillable = [
        'book_id',
        'shelf_id',
        'inventory_code',
        'condition',
        'status',
        'procured_at'
    ];

    protected $casts = [
        'procured_at' => 'date'
    ];

    public function book() 
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function shelf() 
    {
        return $this->belongsTo(Shelf::class, 'shelf_id');
    }
}
