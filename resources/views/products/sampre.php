<?php
コントローラー 
public function index(Request $request) { 
  //モデルをインスタンス化 
  $model = new Product; 　
  //モデルに書き出した検索処理を呼び出して、結果を$productsに詰める 　
  //モデルのsearch関数で入力値が使えるように、引数（かっこのなか）に$requestを渡す 
  $products = $model->search($request); 
  return view('products.index', compact('products')); 
} 

  モデル 
  //コントローラーから渡される$requestを使用するため、カッコ内に記載 
  public function search($request){ 　
    //もともとはProduct::query()となっていましたが、Productモデル内での記述になるので、自分自身(Product)を呼び出すためにself::queryに変更 
    $query = self::query(); 
    if($search = $request->search){ $query->where('product_name', 'LIKE', "%{$search}%"); } 
    if($min_price = $request->min_price){ $query->where('price', '>=', $min_price); } 
    if($max_price = $request->max_price){ $query->where('price', '<=', $max_price); } 
    if($min_stock = $request->min_stock){ $query->where('stock', '>=', $min_stock); } 
    if($max_stock = $request->max_stock){ $query->where('stock', '<=', $max_stock); } 
    if($sort = $request->sort){ $direction = $request->direction == 'desc' ? 'desc' : 'asc'; 
      $query->orderBy($sort, $direction); 
    } 
    $products = $query->paginate(5); 
    //コントローラーに検索結果を返却 
    return $products; 
  }