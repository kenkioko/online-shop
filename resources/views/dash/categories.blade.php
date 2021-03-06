@extends('layouts.dash')

@section('title', 'Categories')

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
      <a href="{{ route('admin.dash') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Categories</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')


    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('categories.view')
          <button type="button"
            id="view_category"
            class="btn btn-sm btn-outline-primary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="View category"
          >
            <i class="nav-icon far fa-eye"></i>
          </button><!-- /.button -->
          @endcan

          @can('categories.create')
          <a type="button"
            class="btn btn-sm btn-outline-success pop"
            href="{{ route('admin.categories.create') }}"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="New category"
          >
            <i class="nav-icon fas fa-plus"></i>
          </a><!-- /.button -->
          @endcan

          @can('categories.update')
          <button type="button"
            id="edit_table_row"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit category"
          >
            <i class="nav-icon fas fa-edit"></i>
          </button><!-- /.button -->
          @endcan

          @can('categories.delete')
          <button type="button"
            id="delete_table_row"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete category"
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

        @data_table(['table_id' => $table_id])
          @slot('head')
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Parent category</th>
              <th scope="col">Created</th>
              <th scope="col">Updated</th>

              @can('categories.view')
                <th scope="col">View Url</th>
              @endcan

              @can('categories.update')
                <th scope="col">Edit Url</th>
              @endcan

              @can('categories.delete')
                <th scope="col">Delete Url</th>
              @endcan
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

              @can('categories.view')
                <td>{{ route('admin.categories.show', ['category' => $category]) }}</td>
              @endcan

              @can('categories.update')
                <td>{{ route('admin.categories.edit', ['category' => $category]) }}</td>
              @endcan

              @can('categories.delete')
                <td>{{ route('admin.categories.destroy', ['category' => $category]) }}</td>
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

    <!-- Delete Category Modal -->
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
    var table;          //datatable
    var selected_row;   //selected table row
    var action_url;     //url for action on selected row
    var table_id = '{{ $table_id }}';   //datatable table id


    $(function(){
      // hide id and url columns
      table.column(1).visible(false);
      @can('categories.view')
        table.column(6).visible(false);
      @endcan

      @can('categories.update')
        table.column(7).visible(false);
      @endcan

      @can('categories.delete')
        table.column(8).visible(false);
      @endcan


      @canany(['categories.view', 'categories.update', 'categories.delete'])
      function is_selected() {
        var selected = true;

        if (!selected_row) {
          $('#select_item_modal').modal('show');
        }

        return selected;
      }
      @endcanany

      @can('categories.view')
      $('#view_category').click( function () {
        if (is_selected()) {
          action_url = selected_row[6];
          window.location.href = action_url;
        }
      });
      @endcan

      @can('categories.update')
      $('#edit_table_row').click( function () {
        if (is_selected()) {
          action_url = selected_row[7];
          window.location.href = action_url;
        }
      });
      @endcan

      @can('categories.delete')
      $('#delete_table_row').click( function () {
        if (is_selected()) {
          $('.del_category_name').text(selected_row[2]);
          action_url = action_url = selected_row[8];
          $("#delete_category_form").attr('action', action_url);
          $('#delete_modal').modal('show');
        }
      });
      @endcan

    });
  </script>
@endsection
