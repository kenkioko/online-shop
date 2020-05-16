@extends('layouts.dash')

@section('title', 'Users')

@section('page_header')
  @if ($user)
    {{ ucwords($user->name) }}
  @else
    New User
  @endif
@endsection

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
    <li class="breadcrumb-item">
      <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">{{ ucwords($user->name) }}</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')


    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('users.update')
          <a type="button"
            href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit User"
          ><i class="nav-icon fas fa-user-edit"></i></a><!-- /.button -->
          @endcan

          @can('users.delete')
            <button type="button"
              form="delete_user_form"
              class="btn btn-sm btn-outline-danger pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Delete user"
              onclick="on_delete()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endcan

          <a type="button"
            href="{{ route('admin.users.index') }}"
            class="btn btn-sm btn-outline-secondary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Discard changes"
          ><i class="nav-icon fas fa-undo-alt"></i></a><!-- /.button -->
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        <!-- User Details -->
        <div class="p-2">
          <h4>User Details:</h4>
          <hr>

          <div class="d-flex">
            <h5><strong>User name:</strong></h5>
            <p class="ml-2">{{ $user->name }}</p>
          </div>

          <div class="d-flex">
            <h5><strong>User email:</strong></h5>
            <p class="ml-2">{{ $user->email }}</p>
          </div>
        </div>

        @if($shop)
          <!-- Shop Details -->
          <div class="border-top p-2">
            <h4>Shop Details:</h4>
            <hr>

            <div class="d-flex">
              <h5><strong>Shop name:</strong></h5>
              <p class="ml-2">{{ $shop->name }}</p>
            </div>

            <div class="d-flex">
              <h5><strong>Shop address:</strong></h5>
              <address class="ml-2">
                {{ $shop->address->full_address }} <br>
                {{ $shop->address->street }} <br>
                {{ $shop->address->city }} <br>
                {{ $shop->address->state }} <br>
                {{ $shop->address->country }} <br>
              </address>
            </div>
          </div>

          <!-- Items Owned By Shop -->
          <div class="border-top p-2">
            <div class="d-flex">
              <h5><strong>Items owned by shop:</strong></h5>
              <p class="ml-2">(Total: {{ $shop->items()->count() }})</p>
            </div>

            @data_table(['table_id' => 'table_list'])
              @slot('head')
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Stock</th>
                  <th scope="col">Price</th>
                  <th scope="col">Discount</th>
                </tr>
              @endslot

              @foreach ($shop->items()->get() as $index => $item)
                <tr>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->stock }}</td>
                  <td>{{ number_format($item->price, 2) }}</td>
                  <td>{{ $item->discount ?? 0 }}</td>
                </tr>
              @endforeach
            @enddata_table
          </div>
        @endif

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @if ($user)
      @modal(['modal_id' => 'delete_modal'])
        @slot('modal_title')
          Delete '{{ $user->name }}'
        @endslot

        @slot('modal_body')
          <p>Are you sure you want to delete '{{ $user->name }}'.</p>
          <form method="post" class="d-none"
            id="delete_user_form"
            action="{{ route('admin.users.destroy', ['user' => $user]) }}"
          >
            @csrf
            @method('DELETE')
          </form>
        @endslot

        @slot('modal_footer')
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" form="delete_user_form">Delete</button>
        @endslot
      @endmodal

    @endif

  </div>
@endsection

@section('page_js')
  @parent

  <script type="text/javascript">
    function on_delete() {
      $('#delete_modal').modal('show');
    }

    function on_change_userlevel() {
      var user_level = $('#user_level_inp').val();

      if (user_level === 'seller') {
        $('#shop_details').removeClass('d-none');
      } else {
        $('#shop_details').addClass('d-none');
      }
    }
  </script>

  <script type="text/javascript">
    $(function () {
      on_change_userlevel();
    });
  </script>
@endsection
