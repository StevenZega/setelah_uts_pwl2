<?php

namespace App\Http\Controllers;

//import model product
use App\Models\Product;
use App\Models\Category_product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Storage;
//import return type view
use Illuminate\View\View;

//import request
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index(): View
    {
        //get all products
        $product = new Product;
        $products = $product->get_product()->latest()->paginate(10);

        //render view with products
        return view('products.index', compact('products'));
    }

    /**
     * create
     * 
     * @return void
     */
    public function create(): View
    {
        $product = new Category_product();
        $data['categories'] = $product->get_category_product()->get();

        $supplier = new Supplier();
        $data['supplier'] = $supplier->get_supplier()->get();
        return view('products.create', compact('data'));
    }

    /**
     * store
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //var_dump($request);exit;
        //validate form
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'title' => 'required|min:5',
            'product_category' => 'required|integer',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        //menghandle upload file gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $store_image = $image->store('images', 'public');// simpan gambar ke folder penyimpanan

            $product = new Product;
            $insert_product = $product->storeProduct($request, $image);

            return redirect()->route('products.index')->with('success', 'Data Berhasil Disimpan!.');
        }

        return redirect()->route('products.index')->with('error', 'Failed to upload image.');
    }

    /**
     * show
     * 
     * @param mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        //get product by id
        $product_model = new Product;
        $product = $product_model->get_product()->where('products.id', $id)->firstOrFail();

        //Render view with product
        return view('products.show', compact('product'));
    }

    /**
     * edit
     * 
     * @param mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        //get product by id
        $product_model = new Product;
        $data['product'] = $product_model->get_product()->where('products.id', $id)->firstOrFail();

        $category_model = new Category_product();
        $data['categories'] = $category_model->get_category_product()->get();

        $supplier_model = new Supplier();
        $data['suppliers_'] = $supplier_model->get_supplier()->get();

        //render view with product
        return view('products.edit', compact('data'));
    }

    /**
     * update
     * 
     * @param Request $request
     * @param mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
{
    // Validasi form
    $validatedData = $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'title' => 'required|min:5',
        'product_category' => 'required|integer',
        'supplier_id' => 'required|integer',
        'description' => 'required|min:10',
        'price' => 'required|numeric',
        'stock' => 'required|numeric'
    ]);

    $product_model = new Product;
    $data_product = $product_model->get_product()->where('products.id', $id)->firstOrFail();

    // Default: pakai gambar lama
    $name_image = $data_product->image;

    // Kalau user upload gambar baru
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_image = $image->hashName();
        $image->store('images', 'public');

        // Hapus gambar lama (jika ada)
        if ($data_product->image) {
            Storage::disk('public')->delete('images/' . $data_product->image);
        }
    }

    // Data yang akan diupdate
    $updateData = [
        'title' => $request->title,
        'product_category_id' => $request->product_category,
        'supplier_id' => $request->supplier_id,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'image' => $name_image,
    ];

    // Update ke model
    $update_product = $product_model->updateProduct($id, $updateData, $name_image);

    // Redirect ke index
    return redirect()->route('products.index')->with('success', 'Data Berhasil Diubah!');
}


    /**
     * destroy
     * 
     * @param mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        //get product by id
        $product_model = new Product;
        $product = $product_model->get_product()->where('products.id', $id)->firstOrFail();

        //delete old image
        Storage::disk('public')->delete('images/' . $product->image);

        //delete product
        $product->delete();

        //redirect to index
        return redirect()->route('products.index')->with('success', 'Data Berhasil Dihapus!.');
    }
}