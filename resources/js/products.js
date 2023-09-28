$(document).ready(function () {
  // 商品リストを読み込み・更新する関数
  function loadProductList() {
      $.ajax({
          url: "{{ route('products.index') }}",
          method: "GET",
          data: $("#search-form").serialize(), // フォームデータをシリアライズ
          success: function (data) {
              $("#product-list").html(data);
          },
          error: function (xhr) {
              console.error(xhr);
          },
      });
  }

  // 検索フォームの送信を処理
  $("#search-form").on("submit", function (e) {
      e.preventDefault();
      loadProductList();
  });

  // 初期表示時に商品リストを読み込み
  loadProductList();
});