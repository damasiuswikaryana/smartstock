<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Po extends Model
{
    use SoftDeletes;
    protected $guarded  = ['id'];
    protected $dates    = ['deleted_at'];
    protected $table    = 'master_po';

    public function child(): HasMany
    {
        return $this->hasMany(PoChild::class, 'po_id', 'id');
    }

    public function entitas(): BelongsTo
    {
        return $this->belongsTo(Entitas::class, 'entitas_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function adminInputBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adminInput_by', 'id');
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by', 'id');
    }

    public function directorBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id', 'id');
    }

    /**
     * Hitung seluruh komponen total PO.
     * Penambah  : subtotal_ukuran_khusus (ke subtotal), PPH, PPN, ongkir, biaya_layanan
     * Pengurang : disc / disc_perc, disc_voucher_toko, potongan, dp
     * total_gross diinput manual oleh user dan tidak ikut dihitung.
     */
    public function calculateTotals(): array
    {
        $children = $this->child;

        $subtotal_items = $children->sum(fn($c) => $c->unit_price * $c->qty);
        $subtotal_pph   = $children->where('pph', 1)->sum(fn($c) => $c->unit_price * $c->qty);
        $ukuran_khusus  = (float) $this->subtotal_ukuran_khusus;
        $subtotal       = $subtotal_items + $ukuran_khusus;

        if ($this->disc != NULL) {
            $disc_amount = (float) $this->disc;
            $disc_perc   = $subtotal > 0 ? ($disc_amount / $subtotal) * 100 : 0;
        } else {
            $disc_perc   = (float) $this->disc_perc;
            $disc_amount = $disc_perc / 100 * $subtotal;
        }
        $voucher_toko     = (float) $this->disc_voucher_toko;
        $potongan         = (float) $this->potongan;
        $total_after_disc = $subtotal - $disc_amount - $voucher_toko - $potongan;

        $tax_amount      = (float) $this->tax / 100 * $subtotal_pph;
        $ppn_amount      = (float) $this->ppn / 100 * $total_after_disc;
        $total_after_tax = $total_after_disc + $tax_amount + $ppn_amount;

        $ongkir        = (float) $this->ongkir;
        $biaya_layanan = (float) $this->biaya_layanan;
        $grand_total   = $total_after_tax + $ongkir + $biaya_layanan;

        $total_after_dp = $grand_total - (float) $this->dp;

        return compact(
            'subtotal_items',
            'ukuran_khusus',
            'subtotal',
            'disc_perc',
            'disc_amount',
            'voucher_toko',
            'potongan',
            'total_after_disc',
            'tax_amount',
            'ppn_amount',
            'total_after_tax',
            'ongkir',
            'biaya_layanan',
            'grand_total',
            'total_after_dp'
        );
    }
}
