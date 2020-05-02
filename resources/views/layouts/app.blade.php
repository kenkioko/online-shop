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
      @nav()
        <!-- print navigation bar -->
      @endnav
    </div>

    <div id="app">
        @yield('content')
    </div>

    @section('footer')
      <!-- Footer -->
      <div class="container-fluid bg-secondary p-5" id="footer">
        <h4>Lorem Ipsum dolor</h4>
        <p>
          Lorem Ipsum dolor sit amet, consectetur adipiscing elit,
          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
          Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
          ut aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
          Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
          deserunt mollit anim id est laborum.
        </p>
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
