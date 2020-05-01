@extends('layouts.dash')

@section('page_css')
  @parent

  <link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"
  >
@endsection

@section('page_header', 'Users')

@section('sidebar')
  @dash_sidebar(['page' => 'users'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dash') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Users</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')
     

    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('users.view')
          <button type="button"
            id="view_btn"
            class="btn btn-sm btn-outline-primary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="View User"
          ><i class="nav-icon far fa-eye"></i></button><!-- /.button -->
          @endcan

          @can('users.create')
          <a type="button"
            class="btn btn-sm btn-outline-success pop"
            href="{{ route('admin.users.create') }}"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="New User"
          ><i class="nav-icon fas fa-user-plus"></i></a><!-- /.button -->
          @endcan

          @can('users.update')
          <button type="button"
            id="edit_table_row"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit User"
          ><i class="nav-icon fas fa-user-edit"></i></button><!-- /.button -->
          @endcan

          @can('users.delete')
          <button type="button"
            id="delete_table_row"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete User"
          ><i class="nav-icon fas fa-user-times"></i></button><!-- /.button -->
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
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">User Level</th>
              <th scope="col">Created</th>
              <th scope="col">Updated</th>

              @can('users.view')
                <th scope="col">View Url</th>
              @endcan

              @can('users.update')
                <th scope="col">Edit Url</th>
              @endcan

              @can('users.delete')
                <th scope="col">Reject Url</th>
              @endcan
            </tr>
          @endslot

          @foreach($users as $user)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
              <td>{{ $user->created_at }}</td>
              <td>{{ $user->updated_at }}</td>

              @can('users.view')
                <td>{{ route('admin.users.show', ['user' => $user]) }}</td>
              @endcan

              @can('users.update')
                <td>{{ route('admin.users.edit', ['user' => $user]) }}</td>
              @endcan

              @can('users.delete')
                <td>{{ route('admin.users.destroy', ['user' => $user]) }}</td>
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
        Delete '<span class="del_user_name"></span>'
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to delete '<span class="del_user_name"></span>'.</p>
        <form method="post" class="d-none" id="delete_user_form">
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="delete_user_form">Delete</button>
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

      @can('users.view')
        table.column(7).visible(false);
      @endcan

      @can('users.update')
        table.column(8).visible(false);
      @endcan

      @can('users.delete')
        table.column(9).visible(false);
      @endcan

      @canany(['users.view', 'users.update', 'users.delete'])
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
          action_url = selected_row[7];
          window.location.href = action_url;
        }
      });
      @endcan

      @can('users.update')
      // selected row actions
      $('#edit_table_row').click( function () {
        if (is_selected()) {
          action_url = selected_row[8];
          window.location.href = action_url;
        }
      });
      @endcan

      @can('users.delete')
      $('#delete_table_row').click( function () {
        if (is_selected()) {
          $('.del_user_name').text(selected_row[2]);
          action_url = selected_row[9];
          $("#delete_user_form").attr('action', action_url);
          $('#delete_modal').modal('show');
        }
      });
      @endcan

    });
  </script>
@endsection
