<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    public function search($request){
        $query = self::query();
        if($search = $request->search){ $query->where('product_name', 'LIKE', "%{$search}%"); }
        if($company_id = $request->company_id){$query->where('company_id', 'LIKE', "$company_id");}
        if($min_price = $request->min_price){ $query->where('price', '>=', $min_price); }
        if($max_price = $request->max_price){ $query->where('price', '<=', $max_price); }
        if($min_stock = $request->min_stock){ $query->where('stock', '>=', $min_stock); }
        if($max_stock = $request->max_stock){ $query->where('stock', '<=', $max_stock); }
        if($sort = $request->sort){ $direction = $request->direction == 'desc' ? 'desc' : 'asc';  
          $query->orderBy($sort, $direction);
        }
        $products = $query->paginate(5);

        return $products;
    }
    
    public function store($request){
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
        return redirect('products');
    }

    // public function update($request){

    //     // エラー発生中
    //     // [  ?  ]

    //     $product->product_name = $request->product_name;
    //     $product->company_id = $request->company_id;
    //     $product->price = $request->price;
    //     $product->stock = $request->stock;
    //     $product->comment = $request->comment;

    //     if($request->hasFile('img_path')){
    //         $filename = $request->img_path->getClientOriginalName();
    //         $filePath = $request->img_path->storeAs('products', $filename, 'public');
    //         $product->img_path = '/storage/' . $filePath;
    //     }

    //     $product->save();
    //     redirect()->route('products.index')->with('success', 'Product updated successfully');
    // }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
