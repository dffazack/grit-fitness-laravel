<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- TAMBAHKAN INI

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'transaction_code',
        'package', // <-- Kunci relasi (string)
        'amount',
        'proof_url',
        'status',
        'admin_note',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ==============================================================
    //  TAMBAHKAN FUNGSI INI (INI UNTUK FIX ERROR DI CONTROLLER)
    // ==============================================================
    /**
     * Mendapatkan paket membership yang dibeli dalam transaksi ini.
     * Relasi ini mencocokkan kolom string 'package' di tabel 'transactions'
     * dengan kolom string 'name' di tabel 'membership_packages'.
     */
    public function membership(): BelongsTo
    {
        // Pastikan nama modelnya 'MembershipPackage'
        return $this->belongsTo(MembershipPackage::class, 'package', 'name');
    }
    // ==============================================================


    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ... (sisa file Anda tidak perlu diubah) ...
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helpers
    public function getFormattedAmount()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'approved' => 'bg-success',
            'pending' => 'bg-warning',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'approved' => 'Disetujui',
            'pending' => 'Menunggu',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}

