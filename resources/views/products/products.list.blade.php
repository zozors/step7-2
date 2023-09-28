<div id="product-list">
    <h2>商品情報</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品名</th>
                <th>メーカー</th>
                <th>価格
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) }}">⬆︎</a>
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) }}">⬇️</a>
                </th>
                <th>
                  在庫数
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'asc']) }}">⬆︎</a>
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'desc']) }}">⬇︎</a>
                </th>
                <th>コメント</th>
                <th>商品画像</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                  <td>{{ $product->id }}</td>
                  <td>{{ $product->product_name }}</td>
                  <td>{{ $product->company->company_name }}</td>
                  <td>{{ $product->price }}</td>
                  <td>{{ $product->stock }}</td>
                  <td>{{ $product->comment }}</td>
                  <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                  <td>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm mx-1">編集</a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $products->appends(request()->query())->links() }}
