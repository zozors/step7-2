<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        $companies = Company::all();
        $model = new Product;
        $products = $model->search($request);
        return view('products.index', ['products' => $products], compact('companies'));
    }
    
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    
    public function store(Request $request)
    {
            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required',
                'price' => 'required|integer',
                'stock' => 'required|integer',
                'comment' => 'nullable',
                'img_path' => 'nullable|image|max:2048',
            ],
            [
                'product_name.required' => '商品名は必須です',
                'company_id.required' => 'メーカー名は必須です',
                'price.required' => '価格は必須です',
                'price.integer' => '半角数字で必須入力',
                'stock.required' => '在庫数は必須です',
                'stock.integer' => '在庫数は半角数字で必須です',
                'img_path.image' => '画像ファイルを選択してください',
                'img_path.max:2048' => '最大2048KBまでです' 
            ]);

            // DB::transaction(function () use($request) {

            $model = new Product;
            $products = $model->store($request);

        return redirect('products');
    }


    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }


    public function edit(Product $product)
    {
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    
    public function update(Request $request, Product $product)
    {
            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'comment' => 'nullable',
                'img_path' => 'nullable|image|max:2048',
            ],
            [
                'product_name.required' => '商品名は必須です',
                'company_id.required' => 'メーカー名は必須です',
                'price.required' => '価格は必須です',
                'stock.required' => '在庫は必須です',
                'img_path.image' => '画像ファイルを選択してください',
                'img_path.max:2048' => '最大2048KBまでです' 
            ]);

            // DB::transaction(function () use($request) {

            // $model = new Product;
            // $products = $model->update($request);

            
            $product->product_name = $request->product_name;
            $product->company_id = $request->company_id;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->comment = $request->comment;

            if($request->hasFile('img_path')){
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $filename, 'public');
                $product->img_path = '/storage/' . $filePath;
            }

            $product->save();
        // });
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    
    public function destroy(Product $product)
    {
        // DB::transaction(function () use ($request) {
            $product->delete();

        // });

        return redirect('/products');
    }
}
