@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-4">商品情報一覧</h1>

  <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

  <div class="search mt-5">
    <h2>検索条件で絞り込み</h2>
    <form id="search-form" method="GET" class="row g-3">
      <div class="col-sm-12 col-md-3">
        <input type="text" name="search" class="form-control" placeholder="商品名" value="{{ request('search') }}">
      </div>
      <div class="col-sm-12 col-md-2">
        <select type="text" name="company_id" id= "company_id" class="form-control"  value="{{ request('company_id') }}">
        <option disabled style='display:none;' @if (empty($product->company_id)) selected @endif>選択してください</option>
          @foreach($companies as $company)
            <option value="{{ $company->id }}"> {{ $company->company_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-12 col-md-2">
        <input type="number" name="min_price" class="form-control" placeholder="最小価格" value="{{ request('min_price') }}">
      </div>
      <div class="col-sm-12 col-md-2">
        <input type="number" name="max_price" class="form-control" placeholder="最大価格" value="{{ request('max_price') }}">
      </div>
      <div class="col-sm-12 col-md-2">
        <input type="number" name="min_stock" class="form-control" placeholder="最小在庫" value="{{ request('min_stock') }}">
      </div>
      <div class="col-sm-12 col-md-2">
        <input type="number" name="max_stock" class="form-control" placeholder="最大在庫" value="{{ request('max_stock') }}">
      </div>
      <div class="col-sm-12 col-md-1">
        <button class="btn btn-outline-secondary" type="submit">絞り込み</button>
      </div>
    </form>
  </div>

  <a href="{{ route('products.index') }}" class="btn btn-success mt-3">検索条件を元に戻す</a>

  <div class="products mt-5", id="product-list">
    <h2>商品情報</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => 'asc']) }}">⬆︎</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => 'desc']) }}">⬇️</a>
          </th>
          <th>商品名
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'product_name', 'direction' => 'asc']) }}">⬆︎</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'product_name', 'direction' => 'desc']) }}">⬇️</a>
          </th>
          <th>メーカー
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'company_id', 'direction' => 'asc']) }}">⬆︎</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'company_id', 'direction' => 'desc']) }}">⬇️</a>
          </th>
          <th>価格
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) }}">⬆︎</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) }}">⬇️</a>
          </th>
          <th>
            在庫数
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'asc']) }}">⬆︎</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'desc']) }}">⬇︎</a>
          </th>
          <th>コメント
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'comment', 'direction' => 'asc']) }}">⬆︎</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'comment', 'direction' => 'desc']) }}">⬇️</a>
          </th>
          <th>商品画像</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($products as $product)
        <tr id="product-{{ $product->id }}">
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
              <button type="submit" class="btn btn-danger btn-sm mx-1 delete-product" data-product-id="{{ $product->id }}" data-delete-url="{{ route('products.destroy', $product) }}">削除</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    {{ $products->appends(request()->query())->links() }}
  </div>
</div>
@endsection
