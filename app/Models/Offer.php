<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'original_price',
        'discount_price',
        'start_date',
        'end_date',
        'stock',
        'status',
        'image_url',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'original_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];
}
