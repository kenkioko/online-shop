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
      @if(Auth::user()->can('dashboard.view'))
        <a href="{{ route('admin.dash') }}">Dashboard</a>
      @else
        <a href="{{ route('home.index') }}">Home</a>
      @endif
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


          <div class="card card-outline card-secondary collapsed-card">
            <div class="card-header">
              <h3 class="card-title text-bold">Basic Info</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
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


          <div class="card card-outline card-secondary collapsed-card">
            <div class="card-header">
              <h3 class="card-title text-bold">Contacts</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="form-group col-sm-6">
                  <label for="phone_input">Telephone Number</label>
                  <input type="tel" class="form-control" id="phone_input" name="phone_number"
                    value="{{ old('phone_number') ?? '' }}"
                    pattern="\+[0-9]{3} [0-9]{3} [0-9]{6}"
                  ><small class="text-muted">Format: +254 712 345678</small>
                </div>

                <div class="col-sm-6 border-left">
                  <div class="form-group">
                    <label for="phone_input">Saved Telephone Numbers</label>
                    <ul class="list-group list-group-flush">
                      @foreach($user_phones as $key => $phone)
                        <li class="list-group-item d-flex align-items-center">
                          {{ $phone->phone_number }}

                          @if($phone->verified)
                            <span class="badge badge-pill badge-success mx-2">Verified</span>
                          @else
                            <button type="button" class="btn btn-link"
                              onclick="verify_phone( {{ $key }}, '{{ $phone }}', '{{ route('profile.phone.update', ['phone' => $phone]) }}', '{{ route('ajax.phone.update', ['phone' => $phone]) }}')"
                            >(Not verified) Click to verify!</button>

                            <span id="verify_phone_spinner_{{$key}}" class="d-none">
                              <i class="fas fa-spinner fa-pulse"></i>
                            </span>
                          @endif

                          <button type="button" class="close ml-auto" aria-label="Close"
                            onclick="remove_phone( {{ $key }}, '{{ $phone }}', '{{ route('profile.phone.update', ['phone' => $phone]) }}')"
                          ><span aria-hidden="true">&times;</span></button>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->


          <div class="card card-outline card-secondary collapsed-card">
            <div class="card-header">
              <h3 class="card-title text-bold">Change Password</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
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


          @role('seller')
            <div class="card card-outline card-secondary collapsed-card">
              <div class="card-header">
                <h3 class="card-title text-bold">Shop Details</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                  <label for="shop_name_input">Shop Name:</label>
                  <input type="text" class="form-control" name="shop_name" id="shop_name_input"
                    value="{{ old('shop_name') ?? $user_shop->name }}"
                  >
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="shop_address_input">Shop Address:</label>
                  <textarea class="form-control" name="shop_address" rows="8" cols="80" id="shop_address_input"
                  >{{ old('shop_address') ?? $user_shop->address }}</textarea>
                </div><!-- /.form-group -->
              </div>
              <!-- /.card-body -->
            </div>
          @endrole

        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- Verify Phone Number Modal -->
    @modal(['modal_id' => 'verify_phone_modal'])
      @slot('modal_title')
        Verify Phone Number '<span class="verify_phone_number"></span>'
      @endslot

      @slot('modal_body')
        <form method="post" id="verify_phone_form">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label for="otp_input">A One Time Pin (OTP) has been send to '<span class="verify_phone_number"></span>'.</label>
            <label for="otp_input">Enter The One Time Pin (OTP)</label>
            <input type="text" class="form-control" id="otp_input" placeholder="12345">
          </div>
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" form="verify_phone_form">Verify</button>
      @endslot
    @endmodal

    <!-- remove Phone Number Modal -->
    @modal(['modal_id' => 'remove_phone_modal'])
      @slot('modal_title')
        Remove Phone Number '<span class="remove_phone_number"></span>'
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to remove '<span class="remove_phone_number"></span>'.</p>
        <form method="post" class="d-none" id="remove_phone_form">
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="remove_phone_form">Remove</button>
      @endslot
    @endmodal

  </div>
@endsection

@section('page_js')
  @parent

  <script src="{{ asset('/js/bootstrap.js') }}" charset="utf-8"></script>

  <script type="text/javascript">
    function verify_phone(key, phone_json, form_url, ajax_url) {
      var phone = JSON.parse(phone_json);
      $('#verify_phone_spinner_'+key).removeClass('d-none');

      // set modal variables and show
      $('.verify_phone_number').text(phone.phone_number);
      $("#verify_phone_form").attr('action', form_url);
      $('#verify_phone_modal').modal('show')

      // use axios from 'resources/js/bootstrap.js'
      window.axios({
        method: 'put',
        url: ajax_url,
        data: { validate: 'request_otp' },
      }).then(function (response) {
        // handle success

        console.log(response);
      }).catch(function (error) {
        // handle error
        create_alert(error);
      }).then(function () {
        // always executed
        remove_alert();
        $('#verify_phone_spinner_'+key).addClass('d-none');
      });
    }

    function create_alert(message) {
      // alert div
      var alert_div = document.createElement('div');
      alert_div.classList.add('alert', 'alert-danger', 'alert-dismissible', 'fade', 'show');
      alert_div.setAttribute('role', 'alert');

      // alert div close button
      var close_btn = document.createElement('button');
      close_btn.classList.add('close');
      close_btn.setAttribute('data-dismiss', 'alert');
      close_btn.setAttribute('aria-label', 'Close');
      close_btn.innerHTML = '&times;';

      // alert div paragraph
      var paragraph = document.createElement('p');
      paragraph.innerHTML = message;
      paragraph.classList.add('m-0');

      // alert div append child elements
      alert_div.appendChild(close_btn);
      alert_div.appendChild(paragraph);

      // append page alert
      remove_alert();
      document.getElementById('page_alert').appendChild(alert_div);
    }

    function remove_alert() {
      var page_alert = document.getElementById('page_alert');
      while (page_alert.firstChild) {
        page_alert.removeChild(page_alert.lastChild);
      }
    }

    function remove_phone(key, phone_json, form_url) {
      var phone = JSON.parse(phone_json);

      // set modal variables and show
      $('.remove_phone_number').text(phone.phone_number);
      $("#remove_phone_form").attr('action', form_url);
      $('#remove_phone_modal').modal('show')
    }
  </script>
@endsection
