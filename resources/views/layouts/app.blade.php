<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Site description -->
    <!-- <meta name="description" content="Free Web tutorials">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="John Doe"> -->

    @section('page_css')
        <!-- Custom CSS with Bootstrap CSS-->
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

        <!-- fontawesome -->
        <link rel="stylesheet" href="{{ asset('/fontawesome/css/all.min.css') }}">
    @show

    <title>My Shop &bull; @yield('title')</title>
  </head>
  <body>

    <div id="navigation">
      @nav_bar()
        <!-- print navigation bar -->
      @endnav_bar
    </div>

    <div id="app">
      @yield('content')

      @include('shared.back_top_btn')
    </div>

    @section('footer')
      <!-- Footer -->
      <hr>
      <div class="container bg-light p-5" id="footer">
        <div class="row">
          <div class="col-md-3">
            <h4><b>My Shop</b></h4>
            <p><a class="text-dark" href="#">About</a></p>
            <p><a class="text-dark" href="#">Contact US</a></p>
            <p><a class="text-dark" href="#">Legal</a></p>
            <p><a class="text-dark" href="#">Invite a friend</a></p>
          </div>
          <div class="col-md-3">
            <h4><b>About</b></h4>
            <p><a class="text-dark" href="#">Blog</a></p>
            <p><a class="text-dark" href="#">Our Comunity</a></p>
            <p><a class="text-dark" href="#">Terms and conditions</a></p>
            <p><a class="text-dark" href="#">Data Protection</a></p>
          </div>
          <div class="col-md-6">
            <p>You can drop a line on our social media platforms and share with friends and family.</p>

            <div class="d-flex">
              <a href="#"><i class="fab fa-facebook-square fa-3x text-primary mx-1"></i></a>              
              <a href="#"><i class="fab fa-twitter-square fa-3x text-primary mx-1"></i></a>
              <a href="#"><i class="fab fa-instagram-square fa-3x text-primary mx-1"></i></a>                  
            </div>            
          </div>
        </div>

        <div class="d-flex border-top py-3">
          <p class="mx-auto">&copy; sitename</p>
        </div>
      </div>
      <!-- End Footer -->
    @show

    @section('page_js')
      <!-- JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="{{ asset('/jquery/jquery.slim.min.js') }}"></script>
      <script src="{{ asset('/popper.js/umd/popper.min.js') }}"></script>
      <script src="{{ asset('/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @show
  </body>
</html>
