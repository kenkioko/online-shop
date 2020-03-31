@extends('layouts.dash')

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
      <a href="{{ route('admin.dash') }}">Admin</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    @if ($user)
      <li class="breadcrumb-item active">{{ ucwords($user->name) }}</li>
    @else
      <li class="breadcrumb-item active">New</li>
    @endif
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @show_alert(['errors', $errors])
    @endshow_alert

    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('users.view')
          @if ($user)
            <a type="button"
              class="btn btn-sm btn-outline-primary pop"
              href="{{ route('admin.users.show', ['user' => $user->id]) }}"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="View User"
            ><i class="nav-icon far fa-eye"></i></a><!-- /.button -->
          @endif
          @endcan

          @canany(['users.create','users.update'])
          <button type="submit"
            form="user_form"
            class="btn btn-sm btn-outline-success pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Save changes"
          ><i class="nav-icon fas fa-save"></i></button><!-- /.button -->
          @endcanany

          @can('users.delete')
          @if ($user)
            <button type="button"
              form="delete_user_form"
              class="btn btn-sm btn-outline-danger pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Delete user"
              onclick="on_delete()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endif
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

        @php
          $form_action = route('admin.users.store');
          if ($user) {
            $form_action = route('admin.users.update', ['user' => $user]);
          }
        @endphp

        <form action="{{ $form_action }}" method="post" id="user_form">
          @csrf

          @if ($user)
            @method('PUT')
          @endif

          <div class="row">
            <div class="form-group col">
              <label for="name_input">Name:</label>
              <input type="text"
                id="name_input"
                name="name"
                class="form-control"
                placeholder="Enter the user's name"
                value="@if (old('name')){{ old('name') }} @elseif ($user){{ $user->name }}@endif"
                @if ($user) readonly  @endif
              >
            </div><!-- /.form-group -->
            <div class="form-group col">
              <label for="email_input">Email:</label>
              <input type="email"
                id="email_input"
                name="email"
                class="form-control"
                placeholder="Enter the user's email"
                value="@if (old('email')){{ old('email') }} @elseif ($user){{ $user->email }}@endif"
              >
            </div><!-- /.form-group -->
          </div><!-- /.row -->


          <div class="row">
            <div class="form-group col">
              <label for="password_input">Password:</label>
              <input type="password"
                id="password_input"
                name="password"
                class="form-control"
                placeholder="password"
              >
            </div><!-- /.form-group -->
            <div class="form-group col">
              <label for="password_confirmation_input">Confirm Password:</label>
              <input type="password"
                id="password_confirmation_input"
                name="password_confirmation"
                class="form-control"
                placeholder="confirm password"
              >
            </div><!-- /.form-group -->
          </div><!-- /.row -->


          <div class="form-group">
            <label>Select User Level:</label>
            <select class="custom-select" name="user_level" id="user_level_inp" onchange="on_change_userlevel()">
              <option>Select Role</option>
              @foreach(['admin', 'buyer', 'seller'] as $level)
                <option value="{{ $level }}"
                  @if ((old('user_level') === $level) or ($user and $user->hasRole($level))) selected @endif
                >{{ ucwords($level) }}</option>
              @endforeach
            </select>
          </div><!-- /.form-group -->


          <div class="d-none border-top p-2" id="shop_details">
            <h4>Shop Details:</h4>

            <div class="form-group">
              <label for="password_input">Shop Name:</label>
              <input type="text" class="form-control" placeholder="shop name" name="shop_name"
                value="@if (old('shop_name')) {{ old('shop_name') }} @elseif ($shop) {{ $shop->name }} @endif"
              >
            </div><!-- /.form-group -->
            <div class="form-group">
              <label for="password_confirmation_input">Shop Address:</label>
              <textarea class="form-control" name="shop_address" rows="8" cols="80"
              >@if (old('shop_address')){{ old('shop_address') }} @elseif ($shop){{ $shop->address }}@endif</textarea>
            </div><!-- /.form-group -->
          </div>

        </form>

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
