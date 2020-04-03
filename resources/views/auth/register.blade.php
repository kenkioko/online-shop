@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8" id="card_area">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            <!-- Shop Details -->
                            <div class="col border-left d-none" id="shop_details_div">
                                <h4 class="border-bottom">Shop Details</h4>

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Shop Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="shop_name" type="text" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ old('shop_name') }}" autocomplete="shop_name" autofocus>

                                        @error('shop_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="shop_address" class="col-md-4 col-form-label text-md-right">{{ __('Shop Address') }}</label>

                                    <div class="col-md-6">
                                        <textarea id="shop_address" type="text" class="form-control @error('shop_address') is-invalid @enderror" name="shop_address" value="{{ old('shop_address') }}" autocomplete="shop_address" autofocus></textarea>

                                        @error('shop_address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Form Buttons -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex align-items-center">

                                <div id="shop_check_div" class="form-check m-2 d-none">
                                  <input type="hidden" name="user_role" value="{{ old('user_role') ?? 'buyer' }}" id="user_role_inp">
                                  <input class="form-check-input" type="checkbox" id="shop_check_inp" onchange="toogle_shop_details()">
                                  <label class="form-check-label" for="shop_check_inp">
                                    {{ __('Create a shop') }}
                                  </label>
                                </div><!-- end shop_btn -->

                                <div><button type="submit" class="btn btn-primary">
                                  {{ __('Register') }}
                                </button></div><!-- end submit -->

                                <div id="shop_btn_div">
                                  <span class="mx-2">-{{ __('OR') }}-</span>

                                  <button type="button" class="btn btn-success" onclick="show_shop_details()">
                                    {{ __('Create a shop') }}
                                  </button>
                                </div><!-- end shop_btn -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@endsection

@section('page_js')
  @parent

  <script type="text/javascript">
    function hide_shop_details() {
      $('#shop_btn_div').removeClass('d-none');
      $('#shop_check_div, #shop_details_div').addClass('d-none');
      $('#card_area').addClass('col-md-8').removeClass('col-md-10');

      $('#user_role_inp').val('buyer')
    }

    function show_shop_details() {
      $('#shop_btn_div').addClass('d-none');
      $('#shop_check_div, #shop_details_div').removeClass('d-none');
      $('#card_area').removeClass('col-md-8').addClass('col-md-10');

      $('#user_role_inp').val('seller')
      $('#shop_check_inp').prop("checked", true)
    }

    function toogle_shop_details() {
      if ($('#shop_check_inp').prop("checked")) {
        show_shop_details();
      } else {
        hide_shop_details();
      }
    }
  </script>

  @if(old('user_role') === 'seller')
  <script type="text/javascript">
    $(function () {
      show_shop_details();
    });
  </script>
  @endif
@endsection
