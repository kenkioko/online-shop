@extends('layouts.app')

@section('title', 'Category')

@section('nav_bar')
  @nav(['page' => 'category'])
    <!-- print navigation bar -->
  @endnav
@endsection

@section('content')
  <!-- Breadcrums -->
  <div class="border-bottom p-2 breadcrums">
    <span class="text-muted">Home / Category /</span>
  </div>
  <!-- End Breadcrums -->

  <div class="container my-5 d-flex">

    <div class="row">
      <!-- Side Menu -->
      <div class="col-sm-3">
        <div class="card sub-menu border-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <h4 class="font-weight-bold">Filter By</h4>
              </li>
            <li class="list-group-item">Brand</li>
            <li class="list-group-item">Size</li>
          </ul>
        </div>
      </div>
      <!-- End Side Menu -->

      <!-- Main Content-->
      <div class="col-sm w-100 mx-2">
        <!-- Content Header -->
        <h1 id="category-header" class="font-weight-bold p-2">
          Category Header
        </h1>
        <!-- End Content Header -->

        <!-- Content Body-->
        <div class="row m-2"  id="content-row">

          @for ($i = 0; $i < 20; $i++)
          <!-- Card -->
          <div class="col-sm-3 p-1">
            <div class="card p-0">
              <a href="item.html">
                <img class="w-100" src="https://via.placeholder.com/150x150"/>
              </a>
              <div class="card-body">
                <a href="/item" class="text-dark">
                  <h5 class="card-title">Item Title</h5>
                </a>
                <p class="card-text">Price: 50$.</p>
              </div>
              <div class="card-footer">
                <button class="btn btn-primary w-100 add-cart">
                  ADD TO CART
                </button>
              </div>
            </div>
          </div>
          <!-- End Card -->
          @endfor

        </div>
        <!-- End Content Body-->
      </div>
      <!-- End Main Content-->
    </div>

  </div>
@endsection
