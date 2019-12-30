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

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <p class="m-0">{{ session('success') }}</p>
      </div>
    @endif

    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <button type="button"
            id="view_btn"
            class="btn btn-sm btn-outline-primary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="View in site"
          ><i class="nav-icon far fa-eye"></i></button><!-- /.button -->
          <a type="button"
            class="btn btn-sm btn-outline-success pop"
            href="{{ route('admin.items.create') }}"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="New"
          ><i class="nav-icon fas fa-plus"></i></a><!-- /.button -->
          <button type="button"
            id="edit_btn"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit"
          ><i class="nav-icon fas fa-edit"></i></button><!-- /.button -->
          <button type="button"
            id="delete_btn"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete"
          ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        <table class="table table-sm" id="table_list">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Category</th>
              <th scope="col">Price</th>
              <th scope="col">Stock</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $item->id }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->category->name }}</td>
              <td>{{ $item->price }}</td>
              <td>{{ $item->stock }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

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

    $(function(){
      // hide id column
      table.column(1).visible(false);

      // selected row actions
      $('#view_btn').click( function () {
        if (selected_row) {
          action_url = new URL(
            'items/' + selected_row[1],
            "{{ route('items.index') }}"
          );

          window.location.href = action_url;
        }
      });

      $('#edit_btn').click( function () {
        if (selected_row) {
          action_url = new URL(
            'items/' + selected_row[1] + '/edit',
            "{{ route('admin.items.index') }}"
          );

          window.location.href = action_url;
        }
      });

      $('#delete_btn').click( function () {
        var data = table.row('.selected').data();
        if (data) {
          $('.del_item_name').text(selected_row[2]);

          action_url = new URL(
            'items/' + selected_row[1],
            "{{ route('admin.items.index') }}"
          );

          $("#delete_item_form").attr('action', action_url);
          $('#delete_modal').modal('show');
        }
      });
    });
  </script>
@endsection
