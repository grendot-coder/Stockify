<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'zone',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * Relasi ke produk yang disimpan di rak ini.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'rack_location_id');
    }

    /**
     * Total stok fisik barang yang sedang menempati rak.
     */
    public function getCurrentStockAttribute(): int
    {
        return (int) ($this->products_sum_stock ?? $this->products()->sum('stock'));
    }

    /**
     * Persentase keterisian rak (0 - 100%).
     */
    public function getOccupancyPercentageAttribute(): int
    {
        $capacity = max(1, $this->capacity ?: 100);

        return (int) min(100, round(($this->current_stock / $capacity) * 100));
    }

    /**
     * Status klasifikasi rak: empty | normal | almost_full | full
     */
    public function getStatusAttribute(): string
    {
        $pct = $this->occupancy_percentage;
        $stock = $this->current_stock;

        if ($stock <= 0) {
            return 'empty';
        }

        if ($pct < 75) {
            return 'normal';
        }

        if ($pct < 95) {
            return 'almost_full';
        }

        return 'full';
    }

    /**
     * Label status bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'empty' => 'Kosong (0%)',
            'normal' => 'Tersedia',
            'almost_full' => 'Hampir Penuh',
            'full' => 'Penuh',
        };
    }

    /**
     * Styling dot matrix Tailwind CSS.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'empty' => 'bg-[#343A40] border border-[#495057]/50',
            'normal' => 'bg-[#F5C842] shadow-[0_0_8px_rgba(245,200,66,0.35)]',
            'almost_full' => 'bg-[#FB923C] shadow-[0_0_8px_rgba(251,146,60,0.35)]',
            'full' => 'bg-[#F43F5E] shadow-[0_0_10px_rgba(244,63,94,0.45)]',
        };
    }
}
