<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    protected $fillable = [
        'nama_kasir',
        'email_pembeli',
        'tanggal_transaksi',
    ];

    public function details()
    {
        return $this->hasMany(DetailTransakiPenjualan::class, 'id_transaksi_penjualan');
    }
}