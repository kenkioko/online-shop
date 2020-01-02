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
          <button type="button" class="btn btn-sm btn-outline-primary">
            <i class="nav-icon far fa-eye"></i>
          </button>
          <button type="button" class="btn btn-sm btn-outline-success">
            <i class="nav-icon fas fa-plus"></i>
          </button>
          <button type="button" class="btn btn-sm btn-outline-info">
            <i class="nav-icon fas fa-edit"></i>
          </button>
          <button type="button" class="btn btn-sm btn-outline-danger">
            <i class="nav-icon fas fa-times"></i>
          </button>
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
              <th scope="col">Oder No</th>
              <th scope="col">User</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $order->id }}</td>
              <td>{{ $order->order_no }}</td>
              <td>{{ $order->user->name }}</td>
              <td>{{ $order->total }}</td>
              <td>{{ $order->status }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

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
    src="{{ asset('/js/datatables.js') }}" >
  </script>

  <script type="text/javascript">
  var table;          //datatable
  var selected_row;   //selected table row
  var action_url;     //url for action on selected row

  $(function(){
    // hide id column
    table.column(1).visible(false);
  });
  </script>
@endsection
