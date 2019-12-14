@extends('layouts.app')

@section('title')
  @php
    echo 'Category | ' . ucwords($category->name);
  @endphp
@endsection

@section('content')
  <!-- Breadcrums -->
  <div class="border-bottom p-2 breadcrums">
    <span class="text-muted">
      Home / Category / {{ ucwords($category->name) }} /
    </span>
  </div>
  <!-- End Breadcrums -->

  <div class="container my-5 d-flex">

    <div class="row w-100">
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
        <h1 id="category-header" class="font-weight-bold p-2 border-bottom">
          {{ ucwords($category->name) }}:
        </h1>
        <!-- End Content Header -->

        <!-- Content Body-->
        <div class="card-columns m-2"  id="content-row">

          @foreach ($items as $item)
            <!-- Card -->
            <div class="card p-0">
              <a href="{{ route('item.show',['item' => $item->id]) }}">
                <img class="w-100" src="https://via.placeholder.com/150x150"/>
              </a>
              <div class="card-body">
                <a href="{{ route('item.show',['item' => $item->id]) }}"
                  class="text-dark"
                >
                  <h5 class="card-title">{{ $item->name }}</h5>
                </a>
                <p class="card-text">Ksh. {{ $item->price }}</p>
              </div>
              <div class="card-footer">
                <button class="btn btn-primary w-100 add-cart" onclick="add_to_cart()">
                  ADD TO CART
                </button>
              </div>
            </div>
            <!-- End Card -->
          @endforeach

        </div>
        <!-- End Content Body-->
      </div>
      <!-- End Main Content-->
    </div>

  </div>
@endsection
