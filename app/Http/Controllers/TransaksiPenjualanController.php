<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksiPenjualan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPenjualan::with('details')->latest()->paginate(10);
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
            $request->validate([
                'nama_kasir' => 'required|string',
                'email_pembeli' => 'required|email',
                'tanggal_transaksi' => 'required|date',
                'product_id' => 'required|array',
                'product_id.*' => 'exists:products,id',
                'jumlah_pembelian' => 'required|array',
            ]);

            // Buat transaksi baru
            $transaksi = TransaksiPenjualan::create([
                'nama_kasir' => $request->nama_kasir,
                'email_pembeli' => $request->email_pembeli,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'total_harga' => 0,
            ]);

            $total = 0;

            // Simpan detail transaksi
            foreach ($request->product_id as $index => $productId) {
                $jumlah = $request->jumlah_pembelian[$index];
                $produk = Product::findOrFail($productId);

                $subtotal = $produk->harga * $jumlah;
                $total += $subtotal;

                DetailTransaksiPenjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_product' => $productId,
                    'harga_satuan' => $produk->harga,
                    'jumlah_pembelian' => $jumlah,
                ]);

                $produk->decrement('stock', $jumlah);
            }

            // Update total harga transaksi
            $transaksi->update(['total_harga' => $total]);

            DB::commit();

            // ✅ Kirim email pakai function fix
            $this->sendEmail($transaksi->email_pembeli, $transaksi->id);

            return redirect()->route('transaksis.index')
                ->with('success', 'Transaksi berhasil disimpan dan email telah dikirim.');
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

            // Kembalikan stok lama
            foreach ($transaksi->details as $detail) {
                $produk = Product::find($detail->id_product);
                if ($produk) {
                    $produk->increment('stock', $detail->jumlah_pembelian);
                }
            }

            $transaksi->details()->delete();

            $transaksi->update([
                'nama_kasir' => $request->nama_kasir,
                'email_pembeli' => $request->email_pembeli,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);

            $total = 0;

            foreach ($request->product_id as $index => $productId) {
                $jumlah = $request->jumlah_pembelian[$index];
                $produk = Product::findOrFail($productId);

                if ($produk->stock < $jumlah) {
                    throw new \Exception("Stok untuk {$produk->title} tidak cukup!");
                }

                $subtotal = $produk->harga * $jumlah;
                $total += $subtotal;

                DetailTransaksiPenjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_product' => $productId,
                    'harga_satuan' => $produk->harga,
                    'jumlah_pembelian' => $jumlah,
                ]);

                $produk->decrement('stock', $jumlah);
            }

            $transaksi->update(['total_harga' => $total]);

            DB::commit();

            // ✅ Kirim ulang email setelah update
            $this->sendEmail($transaksi->email_pembeli, $transaksi->id);

            return redirect()->route('transaksis.index')
                ->with('success', 'Transaksi berhasil diperbarui dan email dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = TransaksiPenjualan::findOrFail($id);

            foreach ($transaksi->details as $detail) {
                $produk = Product::find($detail->id_product);
                if ($produk) {
                    $produk->increment('stock', $detail->jumlah_pembelian);
                }
            }

            $transaksi->details()->delete();
            $transaksi->delete();

            DB::commit();
            return redirect()->route('transaksis.index')
                ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

public function sendEmail($to, $id)
{
    // Ambil transaksi berdasarkan ID beserta relasinya
    $transaksi = TransaksiPenjualan::with('details.product')->findOrFail($id);

    // Ambil detail transaksi
    $data = DetailTransaksiPenjualan::where("id_transaksi_penjualan", $id)->get();

    // Hitung total harga seluruh produk
    $total_harga['transaksi'] = 0;
    foreach ($transaksi['details'] as $key => $detail) {


        $hargaSatuan = $detail['product']['price'];
        $subtotal = $detail['jumlah_pembelian'] * $hargaSatuan;
        $total_harga['transaksi'] += $subtotal;
    }

    $transaksi_ = [
        'transaksi' => $transaksi,
        'data' => $data,
        'total_harga' => $total_harga
    ];

    // Kirim email
    Mail::send('sendmail.show', $transaksi_, function ($message) use ($to, $transaksi, $total_harga) {
        $message->to($to)
            ->subject("Transaksi {$transaksi->email_pembeli} - dengan Total tagihan Rp " 
            . number_format($total_harga['transaksi'], 2, ',', '.') . ".");
    });

    return response()->json(['message' => 'Email sent successfully!']);
}
}