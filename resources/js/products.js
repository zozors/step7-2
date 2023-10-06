

$(document).ready(function () {

  

  $("#product-table th a").on("click", function (e) {
    e.preventDefault();
    var sortColumn = $(this).data("sort");
    var sortDirection = $(this).data("direction");
    loadProductList(sortColumn, sortDirection);

    $(this).data("direction", sortDirection === "asc" ? "desc" : "asc");
    });

  
    
  $("#search-form").on("submit", function (e) {
      e.preventDefault();
      loadProductList();

      $(document).on("click", ".delete-product", function () {
        var productId = $(this).data("product-id");

        $.ajax({
            url: '/products/${productId}',
            method: "DELETE",
            success: function () {
                $('#product-${productId}').hide();
            },
            error: function (xhr) {
                console.error(xhr);
            },
        });
      });
  });
});

function loadProductList(sortColumn = 'id', sortDirection = 'asc') {
      $.ajax({
          url: productListUrl,
          method: "GET",

             data: $('#search-form').serialize(),
          dataType: "html",
          success: function (data) {
            let newtable = $(data).find('#product-list')
              $("#product-list").html(newtable);
          },
          error: function (xhr) {
              console.error(xhr);
          },
      });
  }