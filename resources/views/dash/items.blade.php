@extends('layouts.dash')

@section('page_css')
  @parent

  <link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"
  >
@endsection

@section('page_header', 'Items')

@section('sidebar')
  @dash_sidebar(['page' => 'items'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Orders</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')
     

    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('items.view')
          <button type="button"
            id="view_btn"
            class="btn btn-sm btn-outline-primary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="View in site"
          ><i class="nav-icon far fa-eye"></i></button><!-- /.button -->
          @endcan

          @can('items.create')
          <a type="button"
            class="btn btn-sm btn-outline-success pop"
            href="{{ route('admin.items.create') }}"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="New"
          ><i class="nav-icon fas fa-plus"></i></a><!-- /.button -->
          @endcan

          @can('items.update')
          <button type="button"
            id="edit_btn"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit"
          ><i class="nav-icon fas fa-edit"></i></button><!-- /.button -->
          @endcan

          @can('items.delete')
          <button type="button"
            id="delete_btn"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete"
          ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endcan
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        @php
          $table_id = 'table_list';
        @endphp

        @data_table(['table_id' => $table_id])
          @slot('head')
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Category</th>
              <th scope="col">Price</th>
              <th scope="col">Stock</th>

              @can('items.view')
                <th scope="col">View Url</th>
              @endcan

              @can('items.update')
                <th scope="col">Edit Url</th>
              @endcan

              @can('items.delete')
                <th scope="col">Delete Url</th>
              @endcan
            </tr>
          @endslot

          @foreach($items as $item)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->name }}</td>
            <td>
              @show_item_price(['item' => $item])
              @endshow_item_price
            </td>
            <td>{{ number_format($item->stock) }}</td>

            @can('items.view')
              <td>{{ route('items.show', ['item' => $item]) }}</td>
            @endcan

            @can('items.update')
              <td>{{ route('admin.items.edit', ['item' => $item]) }}</td>
            @endcan

            @can('items.delete')
              <td>{{ route('admin.items.destroy', ['item' => $item]) }}</td>
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
        Delete '<span class="del_item_name"></span>'
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to delete '<span class="del_item_name"></span>'.</p>
        <form method="post" class="d-none" id="delete_item_form">
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="delete_item_form">Delete</button>
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
      // hide id and url columns
      table.column(1).visible(false);
      @can('items.view')
        table.column(6).visible(false);
      @endcan

      @can('items.update')
        table.column(7).visible(false);
      @endcan

      @can('items.delete')
        table.column(8).visible(false);
      @endcan

      @canany(['items.view', 'items.update', 'items.delete'])
      function is_selected() {
        var selected = true;

        if (!selected_row) {
          $('#select_item_modal').modal('show');
        }

        return selected;
      }
      @endcanany

      // selected row actions
      @can('items.view')
      $('#view_btn').click( function () {
        if (is_selected('select_item_modal')) {
          action_url = selected_row[6];
          window.location.href = action_url;
        }
      });
      @endcan

      @can('items.update')
      $('#edit_btn').click( function () {
        if (is_selected('select_item_modal')) {
          action_url = selected_row[7];
          window.location.href = action_url;
        }
      });
      @endcan

      @can('items.delete')
      $('#delete_btn').click( function () {
        if (is_selected('select_item_modal')) {
          $('.del_item_name').text(selected_row[2]);
          action_url = selected_row[8];
          $("#delete_item_form").attr('action', action_url);
          $('#delete_modal').modal('show');
        }
      });
      @endcan

    });
  </script>
@endsection
