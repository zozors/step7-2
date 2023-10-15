$(function() {
  $('.btn-danger').on('click', function(event) {
      event.preventDefault();
  var deleteConfirm = confirm('削除してよろしいでしょうか？');
  if(deleteConfirm == true) {
      var clickEle = $(this)
      // 削除ボタンにユーザーIDをカスタムデータとして埋め込んでます。
      var productID = clickEle.attr('data-product-id');
      $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: 'delete'+ productID,
      type: 'POST',
      data: {'id': productID ,
             '_method': 'DELETE'} // DELETE リクエストだよ！と教えてあげる。
      })
  .done(function() {
        // 通信が成功した場合、クリックした要素の親要素の <tr> を削除
      clickEle.parents('tr').remove();
      alert('削除成功！');
      })
  .fail(function() {
      console.log('削除失敗！');
      });
  } else {
      (function(e) {
      e.preventDefault()
      alert('キャンセル！');
      });
  };
  });
});