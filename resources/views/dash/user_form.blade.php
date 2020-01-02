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
          @if ($user)
            <a type="button"
              class="btn btn-sm btn-outline-primary pop"
              href="{{ route('admin.users.show', ['user' => $user->id]) }}"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="View in site"
            >
              <i class="nav-icon far fa-eye"></i>
            </a><!-- /.button -->

            <button type="button"
              form="delete_user_form"
              class="btn btn-sm btn-outline-warning pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Delete user"
              onclick="on_delete()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endif

          <button type="submit"
            form="user_form"
            class="btn btn-sm btn-outline-success pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Save changes"
          ><i class="nav-icon fas fa-save"></i></button><!-- /.button -->
          <a type="button"
            href="{{ route('admin.users.index') }}"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Discard changes"
          ><i class="nav-icon fas fa-times-circle"></i></a><!-- /.button -->
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

          <div class="form-group">
            <label for="name_input">Name:</label>
            <input type="text"
              id="name_input"
              name="name"
              class="form-control"
              placeholder="Enter the user's name"

              @if (old('name'))
                value="{{ old('name') }}"
              @elseif ($user)
                value="{{ $user->name }}"
              @endif

              @if ($user)
                readonly
              @endif
            >
          </div><!-- /.form-group -->
          <div class="form-group">
            <label for="email_input">Email:</label>
            <input type="email"
              id="email_input"
              name="email"
              class="form-control"
              placeholder="Enter the user's email"

              @if (old('email'))
                value="{{ old('email') }}"
              @elseif ($user)
                value="{{ $user->email }}"
              @endif
            >
          </div><!-- /.form-group -->
          <div class="form-group">
            <label>Select User Level:</label>
            <select class="custom-select" name="user_level">
              @foreach(['buyer', 'admin'] as $level)
                @if (old('user_level'))
                  <option selected value="{{ $level }}">{{ ucwords($level) }}</option>
                @elseif ($user and $user->user_level === $level)
                  <option selected value="{{ $level }}">{{ ucwords($level) }}</option>
                @else
                  <option value="{{ $level }}">{{ ucwords($level) }}</option>
                @endif
              @endforeach
            </select>
          </div><!-- /.form-group -->
          <div class="form-group">
            <label for="password_input">Password:</label>
            <input type="password"
              id="password_input"
              name="password"
              class="form-control"
              placeholder="password"
            >
          </div><!-- /.form-group -->
          <div class="form-group">
            <label for="password_confirmation_input">Confirm Password:</label>
            <input type="password"
              id="password_confirmation_input"
              name="password_confirmation"
              class="form-control"
              placeholder="confirm password"
            >
          </div><!-- /.form-group -->
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
  </script>
@endsection
