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
      <a href="{{ route('admin.dash') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Users</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <button type="button" class="btn btn-sm btn-outline-success">
            <i class="nav-icon fas fa-user-plus"></i>
          </button>
          <button type="button" class="btn btn-sm btn-outline-info">
            <i class="nav-icon fas fa-user-edit"></i>
          </button>
          <button type="button" class="btn btn-sm btn-outline-danger">
            <i class="nav-icon fas fa-user-times"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        @data_table(['table_id' => 'table_list'])
          @slot('head')
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">User Level</th>
              <th scope="col">Created</th>
              <th scope="col">Updated</th>
            </tr>
          @endslot

          @foreach($users as $user)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->user_level }}</td>
              <td>{{ $user->created_at }}</td>
              <td>{{ $user->updated_at }}</td>
            </tr>
          @endforeach
        @enddata_table

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

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
    src="{{ route('home.index') }}/js/datatables.js" >
  </script>
@endsection
