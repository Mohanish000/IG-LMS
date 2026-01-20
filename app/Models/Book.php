<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'price',
        'total_quantity',
        'issued_count',
        'available_count',
        'status' 
    ];
    protected $attributes = [
        'status' => 1,
        'issued_count' => 0
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function scopeDeleted($query)
    {
        return $query->where('status', -1);
    }
}
