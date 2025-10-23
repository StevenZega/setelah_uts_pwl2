<?php

namespace App\Http\Controllers;

use App\Models\Category_product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryProductController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        $categories = Category_product::orderBy('id', 'asc')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * store
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_category_name' => 'required|string|max:255|unique:category_product,product_category_name',
        ]);

        Category_product::create($validatedData);

        return redirect()->route('categories.index')->with('success', 'Category berhasil ditambahkan.');
    }

    /**
     * show
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        $category = Category_product::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    /**
     * edit
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $category = Category_product::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * update
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_category_name' => 'required|unique:category_product,product_category_name,' .$id,
        ]);

        $category = Category_product::findOrFail($id);
        $category->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'Category berhasil diubah.');
    }

    /**
     * destroy
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $category = Category_product::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category berhasil dihapus.');
    }
}
