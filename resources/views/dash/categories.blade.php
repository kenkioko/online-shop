@extends('layouts.dash')

@section('page_css')
  @parent

  <link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"
  >
@endsection

@section('page_header', 'Categories')

@section('sidebar')
  @dash_sidebar(['page' => 'categories'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dash') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Categories</li>
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
            id="view_category"
            class="btn btn-sm btn-outline-primary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="View in site"
          >
            <i class="nav-icon far fa-eye"></i>
          </button><!-- /.button -->
          <a type="button"
            class="btn btn-sm btn-outline-success pop"
            href="{{ route('admin.categories.create') }}"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="New"
          >
            <i class="nav-icon fas fa-plus"></i>
          </a><!-- /.button -->
          <button type="button"
            id="edit_table_row"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit"
          >
            <i class="nav-icon fas fa-edit"></i>
          </button><!-- /.button -->
          <button type="button"
            id="delete_table_row"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete"
          >
            <i class="nav-icon fas fa-trash-alt"></i>
          </button><!-- /.button -->
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        @data_table(['table_id' => 'table_list'])
          @slot('head')
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Parent category</th>
              <th scope="col">Created</th>
              <th scope="col">Updated</th>
            </tr>
          @endslot

          @foreach($categories as $category)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $category->id }}</td>
              <td>{{ $category->name }}</td>

              @if ($category->parent_category)
                <td>{{ $category->parent_category->name }}</td>
              @else
                <td>N/A</td>
              @endif

              <td>{{ $category->created_at }}</td>
              <td>{{ $category->updated_at }}</td>
            </tr>
          @endforeach
        @enddata_table

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @modal(['modal_id' => 'delete_modal'])
      @slot('modal_title')
        Delete '<span class="del_category_name"></span>'
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to delete '<span class="del_category_name"></span>'.</p>
        <form method="post" class="d-none" id="delete_category_form">
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="delete_category_form">Delete</button>
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
    var table;

    $(function(){
      // hide id column
      table.column(1).visible(false);

      // selected row actions
      $('#view_category').click( function () {
        var data = table.row('.selected').data();
        if (data) {
          var view_url = new URL(
            'category/' + data[1],
            "{{ route('categories.index') }}"
          );

          window.location.href = view_url;
        }
      });

      $('#edit_table_row').click( function () {
        var data = table.row('.selected').data();
        if (data) {
          var edit_url = new URL(
            'category/' + data[1] + '/edit',
            "{{ route('admin.categories.index') }}"
          );

          window.location.href = edit_url;
        }
      });

      $('#delete_table_row').click( function () {
        var data = table.row('.selected').data();
        if (data) {
          $('.del_category_name').text(data[2]);

          var action_url = new URL(
            'category/' + data[1],
            "{{ route('admin.categories.index') }}"
          );

          $("#delete_category_form").attr('action', action_url);
          $('#delete_modal').modal('show');
        }
      });
    });
  </script>
@endsection
