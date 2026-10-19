<?php

namespace App\Services\StockOpname;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Repositories\StockOpname\StockOpnameRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\ServiceApi;

class StockOpnameServiceImplement extends ServiceApi implements StockOpnameService
{
    /**
     * Don't remove or change $this->mainRepository variable name
     *
     * @property StockOpnameRepository $mainRepository
     */
    protected $mainRepository;

    public function __construct(StockOpnameRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getPaginated(?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?int $productId = null, int $perPage = 10)
    {
        return $this->mainRepository->getPaginated($search, $dateFrom, $dateTo, $productId, $perPage);
    }

    public function getStats(): array
    {
        return $this->mainRepository->getStats();
    }

    public function createOpname(array $data, int $userId, bool $adjustStock = false)
    {
        return DB::transaction(function () use ($data, $userId, $adjustStock) {
            $product = Product::findOrFail($data['product_id']);
            $systemStock = (int) $product->stock;
            $physicalStock = (int) $data['physical_stock'];
            $difference = $physicalStock - $systemStock;

            $opnameDate = ! empty($data['opname_date'])
                ? Carbon::parse($data['opname_date'])->toDateString()
                : Carbon::now()->toDateString();

            $opname = $this->mainRepository->createOpname([
                'product_id' => $product->id,
                'user_id' => $userId,
                'system_stock' => $systemStock,
                'physical_stock' => $physicalStock,
                'difference' => $difference,
                'notes' => $data['notes'] ?? null,
                'opname_date' => $opnameDate,
            ]);

            // Jika opsi penyesuaian stok dipilih dan terdapat selisih
            if ($adjustStock && $difference !== 0) {
                $product->stock = $physicalStock;
                $product->save();

                // Catat transaksi mutasi penyesuaian opname agar alur mutasi tetap sinkron
                $type = $difference > 0 ? 'in' : 'out';
                $qty = abs($difference);
                $prefix = $type === 'in' ? 'OPN-IN' : 'OPN-OUT';
                $invoiceNum = "TF-{$prefix}-".Carbon::now()->format('YmdHis').'-'.rand(100, 999);

                StockTransaction::create([
                    'invoice_number' => $invoiceNum,
                    'product_id' => $product->id,
                    'supplier_id' => $product->supplier_id,
                    'user_id' => $userId,
                    'type' => $type,
                    'quantity' => $qty,
                    'status' => 'confirmed',
                    'notes' => "Penyesuaian otomatis dari Stock Opname #{$opname->id} (Fisik: {$physicalStock}, Sistem Awal: {$systemStock}, Selisih: {$difference})",
                    'confirmed_by' => $userId,
                    'confirmed_at' => Carbon::now(),
                ]);
            }

            // Catat ke Activity Log
            $diffText = $difference === 0 ? 'Sesuai (0 Unit)' : ($difference > 0 ? "+{$difference} Unit (Surplus)" : "{$difference} Unit (Defisit)");
            $adjustmentText = $adjustStock && $difference !== 0 ? " [Stok sistem disesuaikan menjadi {$physicalStock} Unit]" : '';

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'STOCK_OPNAME',
                'description' => "Melakukan stock opname pada produk '{$product->name}' (SKU: {$product->sku}). Stok Fisik: {$physicalStock}, Sistem: {$systemStock}, Selisih: {$diffText}.{$adjustmentText}",
                'ip_address' => request()->ip(),
            ]);

            return $opname;
        });
    }
}
