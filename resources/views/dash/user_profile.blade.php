@extends('layouts.dash')

@section('title', 'Profile')

@section('page_header', 'User Profile')

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
    <li class="breadcrumb-item active">Profile</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')

    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          <button type="submit"
            form="user_profile_form"
            class="btn btn-sm btn-outline-success pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Save changes"
          ><i class="nav-icon fas fa-save"></i></button><!-- /.button -->

          <a type="button"
            href="{{ Auth::user()->can('dashboard.view') ? route('admin.dash') : route('home.index') }}"
            class="btn btn-sm btn-outline-secondary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Discard changes"
          ><i class="nav-icon fas fa-undo-alt"></i></a><!-- /.button -->

        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form id="user_profile_form" action="{{ route('profile.store') }}" method="post" autocomplete="false">
          @csrf


          <div class="card card-outline card-secondary">
            <div class="card-header">
              <h3 class="card-title text-bold">Basic Info</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="form-group col-sm-6">
                  <label for="name_input">Name</label>
                  <input type="text" class="form-control" id="name_input" name="name" value="{{ old('name') ?? $user->name }}">
                </div>

                <div class="form-group col-sm-6">
                  <label for="email_input">Email address</label>
                  <input type="email" class="form-control" id="email_input" name="email" value="{{ old('email') ?? $user->email }}">
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->


          <div class="card card-outline card-secondary">
            <div class="card-header">
              <h3 class="card-title text-bold">Contacts</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
              <div class="form-group col-sm-6">
                <label for="phone_input">Telephone Number</label>
                <input type="tel" class="form-control" id="phone_input" name="phone" value="{{ old('phone') ?? $user->phone()->get() }}"
                  pattern="+[0-9]{3} [0-9]{3} [0-9]{6}">
                <small class="text-muted px-3">Format: +254 712 345678</small>
              </div>

              <div class="col-sm-6">
                Validated
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->


          <div class="card card-outline card-secondary">
            <div class="card-header">
              <h3 class="card-title text-bold">Change Password</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="form-group col-sm-6">
                  <label for="password_input">Password</label>
                  <input type="password" class="form-control" id="password_input" name="password" placeholder="Password">
                </div>

                <div class="form-group col-sm-6">
                  <label for="password_confirmation_input">Retype Password</label>
                  <input type="password" class="form-control" id="password_confirmation_input" name="password_confirmation" placeholder="Password">
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->


        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

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
