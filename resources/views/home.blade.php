@extends('layouts.app')

@section('title', 'Home')

@section('page_css')
  @parent

  <!-- Flickity CSS -->
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
@endsection

@section('content')
  <!-- Banner Ads -->
  <div class="banner-ads">
    <img src="https://via.placeholder.com/1020x60?text=Banner+Ads" class="w-100" />
  </div>
  <!-- End Banner Ads -->

  <!-- Main Content -->
  <div class="container my-5">
    <!-- Top Page Banners -->
    <div class="banner-main">
      <img src="https://via.placeholder.com/1020x400?text=Banner+Main" class="w-100 py-1"/>
    </div>

    <div class="banner-sub row h-25">
      <img src="https://via.placeholder.com/1020x350?text=Banner+Sub" class="w-100 col-sm-6 py-1" />
      <img src="https://via.placeholder.com/1020x350?text=Banner+Sub" class="w-100 col-sm-6 py-1" />
    </div>
    <!-- End Top Page Banners -->

    @grid()
      <!-- Category Grid inserted -->
    @endgrid

  </div>
  <!-- Main Content -->
@endsection
