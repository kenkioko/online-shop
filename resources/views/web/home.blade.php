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

    <!-- Small ads grid -->
    <div class="container">
      @include('shared.grid', [
        "cell_class" => "w-25",
        "img_style" => "height: 10rem;",
      ])
    </div>  
    <!-- End Small ads grid -->

    <div class="container p-5">
      <!-- Page Tabs -->
      <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item px-4">
          <a class="nav-link active" aria-current="page" href="#">
            <h5><b>NEW PRODUCTS</b></h5>
          </a>
        </li>
        <li class="nav-item px-4">
          <a class="nav-link" href="#">
            <h5><b>NEW SERVICES</b></h5>
          </a>
        </li>
      </ul>

      <!-- items display -->
      @include('shared.item_display')
      <!-- End items display -->

      <!-- Big ads grid -->
      @include('shared.grid', [
        "cell_class" => "w-50",
        "img_style" => "height: 25rem;",
      ])
      <!-- End Big ads grid -->
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
