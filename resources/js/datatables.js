$(function(){

  // Initialize datatables.
  table = $('#table_list').DataTable({
    select: true,
    "columnDefs": [
      { "visible": false, "targets": 1 }
    ]
  });

  // select table single row
  $('#table_list tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
      $(this).removeClass('selected');
    }
    else {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });
});
