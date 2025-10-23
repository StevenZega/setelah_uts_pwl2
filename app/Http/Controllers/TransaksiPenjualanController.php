<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\DetailTransakiPenjualan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPenjualan::with('details')->latest()-> paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $products = Product::orderBy('title')->get();
        return view('transaksi.create', compact('products'));
    }

    public function store(Request $request)
    {

    DB::beginTransaction();
        try {

        // Validasi sederhana
    $request->validate([
        'nama_kasir' => 'required|string',
        'email_pembeli' => 'required|email',
        'tanggal_transaksi' => 'required|date',
        'product_id' => 'required|array',
        'product_id.*' => 'exists:products,id',
        'jumlah_pembelian' => 'required|array',
    ]);

    // Simpan data utama transaksi
    $transaksi = TransaksiPenjualan::create([
        'nama_kasir' => $request->nama_kasir,
        'email_pembeli' => $request->email_pembeli,
        'tanggal_transaksi' => $request->tanggal_transaksi,
        'total_harga' => 0, // bisa dihitung setelah detail masuk
    ]);

    $total = 0;

    // Simpan setiap detail produk
    foreach ($request->product_id as $index => $productId) {
        $jumlah = $request->jumlah_pembelian[$index];
        $produk = Product::find($productId); // pastikan model Produk ada
        $subtotal = $produk->harga * $jumlah;
        $total += $subtotal;

        DetailTransakiPenjualan::create([
            'id_transaksi_penjualan' => $transaksi->id,
            'id_product' => $productId,
            'harga_satuan' => $produk->harga,
            'jumlah_pembelian' => $jumlah,
        ]);

        $produk->decrement('stock', $jumlah); // ✅ otomatis update stok di DB
    }

    // Update total harga di transaksi utama
    $transaksi->update(['total_harga' => $total]);

    DB::commit();
    return redirect()->route('transaksis.index')
                     ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }    

    public function show($id)
    {
        $transaksi = TransaksiPenjualan::with('details.product')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }
    
    public function edit($id)
    {
        $transaksi = TransaksiPenjualan::findOrFail($id);
        $products = Product::all();
        return view('transaksi.edit', compact('transaksi', 'products'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'nama_kasir' => 'required|string',
                'email_pembeli' => 'required|email',
                'tanggal_transaksi' => 'required|date',
                'product_id' => 'required|array',
                'jumlah_pembelian' => 'required|array',
            ]);

            $transaksi = TransaksiPenjualan::findOrFail($id);

            // 🧾 1. Balikin dulu stok lama
            foreach ($transaksi->details as $detail) {
                $produk = Product::find($detail->id_product);
                if ($produk) {
                    $produk->increment('stock', $detail->jumlah_pembelian); // stok dikembalikan
                }
            }

            // 🧹 2. Hapus detail lama biar diganti sama data baru
            $transaksi->details()->delete();

            // 🔄 3. Update data transaksi utama
            $transaksi->update([
                'nama_kasir' => $request->nama_kasir,
                'email_pembeli' => $request->email_pembeli,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);

            // 💰 4. Tambahkan detail baru & kurangi stok lagi
            $total = 0;
            foreach ($request->product_id as $index => $productId) {
                $jumlah = $request->jumlah_pembelian[$index];
                $produk = Product::find($productId);

                if (!$produk) continue;

                // Cek stok cukup
                if ($produk->stock < $jumlah) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stok untuk {$produk->title} tidak cukup!");
                }

                $subtotal = $produk->harga * $jumlah;
                $total += $subtotal;

                // Simpan detail baru
                DetailTransakiPenjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_product' => $productId,
                    'harga_satuan' => $produk->harga,
                    'jumlah_pembelian' => $jumlah,
                ]);

                // Kurangi stok sesuai jumlah baru
                $produk->decrement('stock', $jumlah);
            }

            // Update total harga
            $transaksi->update(['total_harga' => $total]);

            DB::commit();

            return redirect()->route('transaksis.index')
                            ->with('success', 'Transaksi berhasil diperbarui dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
    DB::beginTransaction();

    try {
        $transaksi = TransaksiPenjualan::findOrFail($id);

        // 🔁 Kembalikan stok semua produk yang ada di transaksi ini
        foreach ($transaksi->details as $detail) {
            $produk = Product::find($detail->id_product);
            if ($produk) {
                $produk->increment('stock', $detail->jumlah_pembelian);
            }
        }

        // 🧹 Hapus semua detail transaksi dulu
        $transaksi->details()->delete();

        // ❌ Hapus transaksi utamanya
        $transaksi->delete();

        DB::commit();
        return redirect()->route('transaksis.index')->with('success', 'Transaksi berhasil dihapus dan stok produk dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}