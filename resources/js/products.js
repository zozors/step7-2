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

$(function() {
    $('.delete-product').on('click', function(e) {
        e.preventDefault();

        var productID = $(this).data('product-id');
        var deleteUrl = $(this).data('delete-url');

        var deleteConfirm = confirm('削除してもよろしいでしょうか？');

        if (deleteConfirm) {
            var clickEle = $(this);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: deleteUrl,
                type: 'POST',
                data: {'id': productID, '_method': 'DELETE'},
                success: function (data) {
                    if (data.success) {
                        var redirectTo = decodeURIComponent(data.redirectTo);
                        window.location.href = redirectTo;
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                },
            })

            .done(function() {
                clickEle.parents('tr').remove();
            })

            .fail(function() {
                alert('エラー');
            });
        }
    });
});


// $(document).on("click", ".delete-product", function () {

//         e.preventDefault();
//         console.log('hello');
//         var productId = $(this).data("product-id");
//         var deleteUrl = "{{ route('products.destroy', ':id') }}".replace(':id', productId);

//         $.ajax({
//             url: deleteUrl,
//             method: "DELETE",
//             success: function () {
//                 $('#product-' + productId).hide();
//             },
//             error: function (xhr) {
//                 console.error(xhr);
//             },
//         });
// });