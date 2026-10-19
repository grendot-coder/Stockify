<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'system_stock',
        'physical_stock',
        'difference',
        'notes',
        'opname_date',
    ];

    protected $casts = [
        'system_stock' => 'integer',
        'physical_stock' => 'integer',
        'difference' => 'integer',
        'opname_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
