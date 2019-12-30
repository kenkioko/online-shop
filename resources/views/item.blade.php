@extends('layouts.app')

@section('title')
  @php
    echo ucwords($item->name);
  @endphp
@endsection

@section('page_css')
  @parent

  <!-- Flickity CSS -->
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
@endsection

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item">
      <a href="{{ route('categories.show',['category' => $item->category->id]) }}">
        {{ ucwords($item->category->name) }}
      </a>
    </li>
    <li class="breadcrumb-item active">{{ ucwords($item->name) }}</li>
  @endbreadcrum()


  <div class="container my-5">
    <!-- Item Description -->
    <div class="row">
      <!-- Item Images -->
      <div class="col-sm-6 d-flex flex-column">
        <img class="w-100" src="https://via.placeholder.com/500x300"/>

        <div class="grid-container" id="item-images-grid">
          <div class="grid-item">
            <img src="https://via.placeholder.com/100x100"/>
          </div>
          <div class="grid-item">
            <img src="https://via.placeholder.com/100x100"/>
          </div>
          <div class="grid-item">
            <img src="https://via.placeholder.com/100x100"/>
          </div>
          <div class="grid-item">
            <img src="https://via.placeholder.com/100x100"/>
          </div>
          <div class="grid-item">
            <img src="https://via.placeholder.com/100x100"/>
          </div>
        </div>
      </div>
      <!-- End Item Images -->

      <div class="col-sm-6 px-5">
        <h1 class="border-bottom" id="item-title">
          {{ ucwords($item->name) }}
        </h1>

        <p class="my-4">
          <span class="font-weight-bold">
            Description:
          </span><br>
          {{ ucwords($item->description) }}
        </p>

        <p><span class="font-weight-bold">
            Select Size:
        </span><br></p>
        <div class="grid-sizes">
          <div class="size-item border">7</div>
          <div class="size-item border">8</div>
          <div class="size-item border">9</div>
          <div class="size-item border">10</div>
          <div class="size-item border">11</div>
        </div>

        <button class="btn btn-primary w-100 add-cart my-5"
          onclick="add_to_cart()"
        >
          ADD TO CART
        </button>
      </div>
    </div>
    <!-- End Item Description -->

    <hr>

    <!-- More Items Grid -->
    <div class="d-flex flex-column mt-5">
      <h3 id="category-header" class="mx-auto">You might also like</h3>

      <div class="grid-container" id="more-items-grid">
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 1</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 2</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 3</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 4</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 5</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 6</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 7</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 8</p>
        </div>
        <div class="grid-item">
          <img src="https://via.placeholder.com/150x150"/>
          <p>Item 9</p>
        </div>
      </div>
    </div>
    <!-- More Items Grid -->

  </div>
@endsection

@section('page_js')
  @parent

  <!-- Flickity JavaScript -->
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script src="{{ asset('/js/flikty.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
    function add_to_cart() {
      console.log('add to cart');
    }
  </script>
@endsection
