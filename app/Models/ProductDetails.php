<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'img1',
        'img2',
        'img3',
        'img4',
        'des',
        'description',
        'color',
        'size',
        'product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
