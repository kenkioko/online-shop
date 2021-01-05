@extends('layouts.app')

@section('title', 'Home')

@section('page_css')
  @parent

  <!-- Flickity CSS -->
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
@endsection

@section('content')
  <!-- Main Content -->
  <div class="container-fluid p-0">
    <!-- Top Page Banners -->
    <div class="banner-main">
      @carousel
      @endcarousel
    </div>
    <!-- End Top Page Banners -->

    <div class="container">
      @include('shared.grid')
    </div>    

  </div>
  <!-- Main Content -->
@endsection

@section('page_js')
  @parent

  <!-- Flickity JavaScript -->
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script src="{{ asset('/js/flikty.js') }}" type="text/javascript"></script>
@endsection
