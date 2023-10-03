console.log('Hello World');

$(document).ready(function () {
    // 初期表示時に全ての商品をロード
    loadProductList();
  // 商品リストを読み込み・更新する関数
  function loadProductList(sortColumn = 'id', sortDirection = 'asc') {
      $.ajax({
          url: productListUrl,
          method: "GET",
          data: {
            sort: sortColumn,
            direction: sortDirection,
            search: $("#search-form input[name='search']").val(),
            min_price: $("#search-form input[name='min_price']").val(),
            max_price: $("#search-form input[name='max_price']").val(),
            min_stock: $("#search-form input[name='min_stock']").val(),
            max_stock: $("#search-form input[name='max_stock']").val(),
            company_id: $("#search-form select[name='company_id']").val(),
        },
          success: function (data) {
              $("#product-list").html(data);
          },
          error: function (xhr) {
              console.error(xhr);
          },
      });
  }
  // 商品リストのソートヘッダーがクリックされたときにソートを切り替える
  $("#product-table th a").on("click", function (e) {
    e.preventDefault();
    var sortColumn = $(this).data("sort");
    var sortDirection = $(this).data("direction");
    loadProductList(sortColumn, sortDirection);
    // ソート方向を切り替える
    $(this).data("direction", sortDirection === "asc" ? "desc" : "asc");
    });

  // 検索フォームの送信を処理
  $("#search-form").on("submit", function (e) {
      e.preventDefault();
      loadProductList();
  });
});