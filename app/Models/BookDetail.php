<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_id',
        'description',
        'publisher',
        'publication_year',
        'language',
        'pages',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
