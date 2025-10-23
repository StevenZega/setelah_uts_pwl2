<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransakiPenjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi_penjualan';

    public $timestamps = false;

    protected $fillable = [
        'id_transaksi_penjualan',
        'id_product',
        'jumlah_pembelian',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi_penjualan');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
