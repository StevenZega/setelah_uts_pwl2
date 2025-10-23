<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'image',
        'title',
        'product_category_id',
        'supplier_id',
        'description',
        'price',
        'stock'
    ];

    public function get_product()
    {
        $sql = $this->select(
            "products.*",
            "category_product.product_category_name as product_category_name",
            "suppliers.supplier_name as supplier_name"
        )
            ->leftjoin('category_product', 'category_product.id', '=', 'products.product_category_id')
            ->leftjoin('suppliers', 'suppliers.id', '=', 'products.supplier_id');

        return $sql;
    }

    public static function storeProduct($request, $image)
    {
        return self::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'supplier_id' => $request->supplier_id,
            'product_category_id' => $request->product_category,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->jumlah
        ]);
    }

    public static function updateProduct($id, $request, $image = null)
    {
        $product = self::find($id);

        if ($product) {
            $data = [
                'title' => $request['title'],
                'product_category_id' => $request['product_category_id'],
                'supplier_id' => $request['supplier_id'],
                'description' => $request['description'],
                'price' => $request['price'],
                'stock' => $request['stock']
            ];

            if (!empty($image)) {
                $data['image'] = $image;
            }

            $product->update($data);
            return $product;
        } else {
            return "tidak ada data yang diupdate";
        }
    }


}

