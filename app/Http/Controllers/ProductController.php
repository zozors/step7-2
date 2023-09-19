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
        
        $query = Product::query();

        if($search = $request->search){
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        if($company_id = $request->company_id){
            $query->where('company_id', 'LIKE', "%{$company_id}%");
        }
        
        if($min_price = $request->min_price){
            $query->where('price', '>=', $min_price);
        }

        if($max_price = $request->max_price){
            $query->where('price', '<=', $max_price);
        }

        if($min_stock = $request->min_stock){
            $query->where('stock', '>=', $min_stock);
        }

        if($max_stock = $request->max_stock){
            $query->where('stock', '<=', $max_stock);
        }

        if($sort = $request->sort){
            $direction = $request->direction == 'desc' ? 'desc' : 'asc';
            $query->orderBy($sort, $direction);
        }

        $products = $query->paginate(5);
        $companies = Company::all();
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

            // DB::beginTransaction();

        // try {
            $product = new Product([
                'product_name' => $request->get('product_name'),
                'company_id' => $request->get('company_id'),
                'price' => $request->get('price'),
                'stock' => $request->get('stock'),
                'comment' => $request->get('comment'),
            ]);

            if($request->hasFile('img_path')){
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $filename, 'public');
                $product->img_path = '/storage/' . $filePath;
            }

            $product->save();
        //     DB::commit();
        // } catch (Exception $e) {
        //     DB::rollback();
        //     Log::error($e);
        // }

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
        DB::beginTransaction();

        try {
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
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            $product->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
        }

        return redirect('/products');
    }
}
