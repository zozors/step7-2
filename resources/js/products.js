

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
  });
});

function loadProductList(sortColumn = 'id', sortDirection = 'asc') {
    console.log('Hello World');
      $.ajax({
          url: productListUrl,
          method: "GET",

             data:$('#search-form').serialize(),
        //   data: {
        //     sort: sortColumn,
        //     direction: sortDirection,
        //     search: $("#search-form input[name='search']").val(),
        //     min_price: $("#search-form input[name='min_price']").val(),
        //     max_price: $("#search-form input[name='max_price']").val(),
        //     min_stock: $("#search-form input[name='min_stock']").val(),
        //     max_stock: $("#search-form input[name='max_stock']").val(),
        //     company_id: $("#search-form select[name='company_id']").val(),
        //   },
          dataType:"html",
          success: function (data) {
            console.log('Hello');
            let newtable = $(data).find('#product-list')
              $("#product-list").html(newtable);
          },
          error: function (xhr) {
              console.error(xhr);
          },
      });
  }