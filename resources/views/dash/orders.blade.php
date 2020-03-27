@extends('layouts.dash')

@section('page_css')
  @parent

  <link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"
  >
@endsection

@section('page_header', 'Orders')

@section('sidebar')
  @dash_sidebar(['page' => 'orders'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dash') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Orders</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('orders.update')
          <button type="button"
            id="edit_table_row"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Process Order"
          >
            <i class="nav-icon far fa-eye"></i>
          </button><!-- /.button -->
          @endcan

          @can('orders.delete')
          <button type="button"
            id="delete_table_row"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete Order"
          >
            <i class="nav-icon fas fa-trash-alt"></i>
          </button><!-- /.button -->
          @endcan
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        @php
          $table_id = 'table_list';
        @endphp

        @data_table(['table_id' => $table_id ])
          @slot('head')
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">Order No</th>
              <th scope="col">Item Name</th>
              <th scope="col">User</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>

              @can('orders.update')
                <th scope="col">Process Url</th>
              @endcan

              @can('orders.delete')
                <th scope="col">Reject Url</th>
              @endcan
            </tr>
          @endslot

          @foreach($orders as $key => $order_item)
            @php
              $order = $order_item->order()->first();
              $item = $order_item->item()->first();
            @endphp
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $order->id }}</td>
            <td>{{ $order->order_no }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ number_format($order_item->amount, 2) }}</td>
            <td>{{ App\Item::getStatus($order_item->status, false) }} </td>

            @can('orders.update')
              <td>{{ route('admin.orders.show', ['order' => $order]) }}</td>
            @endcan

            @can('orders.delete')
              <td>{{ route('admin.orders.destroy', ['order' => $order]) }}</td>
            @endcan
          </tr>
          @endforeach
        @enddata_table

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- No Selected Item Modal -->
    @modal(['modal_id' => 'select_item_modal'])
      @slot('modal_title')
        Select Item
      @endslot

      @slot('modal_body')
        <p>Please select an item from the table.</p>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
      @endslot
    @endmodal

    <!-- Delete Item Modal -->
    @modal(['modal_id' => 'delete_modal'])
      @slot('modal_title')
        Delete '<span class="del_order_no"></span>'
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to delete '<span class="del_order_no"></span>'.</p>
        <form method="post" class="d-none" id="del_order_form">
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="del_order_form">Delete</button>
      @endslot
    @endmodal

  </div>
@endsection

@section('page_js')
  @parent

  <script type="text/javascript"
    src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"
    charset="utf-8"
  ></script>

  <script type="text/javascript"
    src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"
    charset="utf-8"
  ></script>

  <script type="text/javascript"
    src="{{ asset('/js/datatables.js') }}" >
  </script>

  <script type="text/javascript">
  var table;          //datatable
  var selected_row;   //selected table row
  var action_url;     //url for action on selected row
  var table_id = '{{ $table_id }}';   //datatable table id

  $(function(){
    // hide id column
    table.column(1).visible(false);

    @can('orders.update')
      table.column(7).visible(false);
    @endcan

    @can('orders.delete')
      table.column(8).visible(false);
    @endcan

    @canany(['orders.view', 'orders.update', 'orders.delete'])
    function is_selected(modal_id) {
      var selected = true;

      if (!selected_row) {
        $('#select_item_modal').modal('show');
      }

      return selected;
    }
    @endcanany

    @can('orders.update')
    // selected row actions
    $('#edit_table_row').click( function () {
      if (is_selected()) {
        action_url = selected_row[7];
        window.location.href = action_url;
      }
    });
    @endcan

    @can('orders.delete')
    $('#delete_table_row').click( function () {
      if (is_selected()) {
        $('.del_order_no').text(selected_row[2]);
        action_url = selected_row[8];
        $("#del_order_form").attr('action', action_url);
        $('#delete_modal').modal('show');
      }
    });
    @endcan
  });
  </script>
@endsection
