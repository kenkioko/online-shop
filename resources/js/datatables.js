$(function(){
  /**
   * Define the following global variables
   * in the pages containing this file 'datatables.js'
   *
   * var table;          //datatable
   * var selected_row;   //selected table row
   */

  // Initialize datatables.
  table = $('#table_list').DataTable({
    select: true,
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

    selected_row = table.row('.selected').data();
  });
});
