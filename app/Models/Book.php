<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    use HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'slug',
        'isbn',
        'description',
        'cover_image',
        'author_id',
        'publisher_id',
        'category_id',
        'publication_year'
    ];

    protected $casts = [
        'publication_year' => 'integer'
    ];

    public function items()
    {
        return $this->hasMany(BookItem::class, 'book_id');
    }
    
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function publisher() 
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
