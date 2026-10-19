<?php

namespace App\Services\StockTransaction;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Repositories\StockTransaction\StockTransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use LaravelEasyRepository\Service;

class StockTransactionServiceImplement extends Service implements StockTransactionService
{
    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(StockTransactionRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getPaginated(?string $search = null, ?string $type = null, ?string $status = null, ?int $productId = null, int $perPage = 10)
    {
        return $this->mainRepository->getPaginated($search, $type, $status, $productId, $perPage);
    }

    public function generateInvoiceNumber(string $type = 'in'): string
    {
        return $this->mainRepository->generateInvoiceNumber($type);
    }

    public function createInbound(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $product = Product::findOrFail($data['product_id']);
            $quantity = intval($data['quantity']);
            $status = $data['status'] ?? 'confirmed';

            $invoiceNumber = ! empty($data['invoice_number'])
                ? $data['invoice_number']
                : $this->mainRepository->generateInvoiceNumber('in');

            $transaction = $this->mainRepository->createTransaction([
                'invoice_number' => $invoiceNumber,
                'product_id' => $product->id,
                'supplier_id' => $data['supplier_id'] ?? null,
                'user_id' => $userId,
                'type' => 'in',
                'quantity' => $quantity,
                'status' => $status,
                'notes' => $data['notes'] ?? null,
                'confirmed_by' => ($status === 'confirmed') ? $userId : null,
                'confirmed_at' => ($status === 'confirmed') ? Carbon::now() : null,
            ]);

            if ($status === 'confirmed') {
                $product->increment('stock', $quantity);

                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => 'INBOUND_TRANSACTION',
                    'description' => "Barang Masuk: {$quantity} unit produk '{$product->name}' (No. Invoice: {$invoiceNumber}). Stok baru: {$product->stock}.",
                    'ip_address' => request()->ip(),
                ]);
            } else {
                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => 'CREATE_INBOUND_PENDING',
                    'description' => "Pengajuan Barang Masuk: {$quantity} unit produk '{$product->name}' (No. Invoice: {$invoiceNumber}) menunggu persetujuan.",
                    'ip_address' => request()->ip(),
                ]);
            }

            return $transaction;
        });
    }

    public function createOutbound(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $product = Product::findOrFail($data['product_id']);
            $quantity = intval($data['quantity']);
            $status = $data['status'] ?? 'confirmed';

            // Validasi kecukupan stok fisik gudang
            if ($product->stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok gudang tidak mencukupi! Stok saat ini untuk '{$product->name}' adalah {$product->stock} unit, sedangkan permintaan pengeluaran adalah {$quantity} unit.",
                ]);
            }

            $invoiceNumber = ! empty($data['invoice_number'])
                ? $data['invoice_number']
                : $this->mainRepository->generateInvoiceNumber('out');

            $transaction = $this->mainRepository->createTransaction([
                'invoice_number' => $invoiceNumber,
                'product_id' => $product->id,
                'supplier_id' => null,
                'user_id' => $userId,
                'type' => 'out',
                'quantity' => $quantity,
                'status' => $status,
                'notes' => $data['notes'] ?? null,
                'confirmed_by' => ($status === 'confirmed') ? $userId : null,
                'confirmed_at' => ($status === 'confirmed') ? Carbon::now() : null,
            ]);

            if ($status === 'confirmed') {
                $product->decrement('stock', $quantity);

                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => 'OUTBOUND_TRANSACTION',
                    'description' => "Barang Keluar: {$quantity} unit produk '{$product->name}' (No. Invoice: {$invoiceNumber}). Stok tersisa: {$product->stock}.",
                    'ip_address' => request()->ip(),
                ]);
            } else {
                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => 'CREATE_OUTBOUND_PENDING',
                    'description' => "Pengajuan Barang Keluar: {$quantity} unit produk '{$product->name}' (No. Invoice: {$invoiceNumber}) menunggu persetujuan.",
                    'ip_address' => request()->ip(),
                ]);
            }

            return $transaction;
        });
    }

    public function confirmTransaction(int $id, int $confirmedBy)
    {
        return DB::transaction(function () use ($id, $confirmedBy) {
            $transaction = $this->mainRepository->find($id);

            if ($transaction->status !== 'pending') {
                return $transaction;
            }

            $product = $transaction->product;

            if ($transaction->type === 'in') {
                $product->increment('stock', $transaction->quantity);
            } elseif ($transaction->type === 'out') {
                if ($product->stock < $transaction->quantity) {
                    throw ValidationException::withMessages([
                        'error' => "Gagal konfirmasi: Stok barang '{$product->name}' saat ini ({$product->stock} unit) tidak mencukupi permintaan pengeluaran ({$transaction->quantity} unit).",
                    ]);
                }
                $product->decrement('stock', $transaction->quantity);
            }

            $updated = $this->mainRepository->updateStatus($id, 'confirmed', $confirmedBy);

            ActivityLog::create([
                'user_id' => $confirmedBy,
                'action' => 'CONFIRM_TRANSACTION',
                'description' => "Menyetujui transaksi {$transaction->invoice_number} ({$transaction->type}) sebanyak {$transaction->quantity} unit produk '{$product->name}'.",
                'ip_address' => request()->ip(),
            ]);

            return $updated;
        });
    }

    public function rejectTransaction(int $id, int $confirmedBy, ?string $reason = null)
    {
        return DB::transaction(function () use ($id, $confirmedBy, $reason) {
            $transaction = $this->mainRepository->find($id);

            if ($transaction->status !== 'pending') {
                return $transaction;
            }

            if (! empty($reason)) {
                $notes = trim(($transaction->notes ?? '')." [Ditolak: {$reason}]");
                $transaction->notes = $notes;
                $transaction->save();
            }

            $updated = $this->mainRepository->updateStatus($id, 'rejected', $confirmedBy);

            ActivityLog::create([
                'user_id' => $confirmedBy,
                'action' => 'REJECT_TRANSACTION',
                'description' => "Menolak transaksi {$transaction->invoice_number} ({$transaction->type}) produk '{$transaction->product->name}'. Alasan: ".($reason ?: 'Tanpa keterangan'),
                'ip_address' => request()->ip(),
            ]);

            return $updated;
        });
    }
}
