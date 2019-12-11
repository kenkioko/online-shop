@extends('layouts.app')

@section('title', 'Item')

@section('page_css')
  @parent

  <!-- Flickity CSS -->
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
@endsection

@section('nav_bar')
  @nav(['page' => 'item'])
    <!-- print navigation bar -->
  @endnav
@endsection

@section('content')
  <!-- Breadcrums -->
  <div class="border-bottom p-2 breadcrums">
    <span class="text-muted">Home / Item Title /</span>
  </div>
  <!-- End Breadcrums -->

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
        <h1 class="border-bottom" id="item-title">Item Title</h1>

        <p class="my-5">
          Ma quande lingues coalesce, li grammatica del resultant lingue es
          plu simplic e regulari quam ti del coalescent lingues. Li nov lingua
          franca va esser plu simplic e regulari quam li existent Europan lingues.
          It va esser tam simplic quam Occidental in fact, it va esser Occidental.
          A un Angleso it va semblar un simplificat Angles, quam un skeptic Cambridge
          amico dit me que Occidental es.
        </p>

        <p>Select Size:</p>
        <div class="grid-sizes">
          <div class="size-item border">7</div>
          <div class="size-item border">8</div>
          <div class="size-item border">9</div>
          <div class="size-item border">10</div>
          <div class="size-item border">11</div>
        </div>

        <a href="#" class="btn btn-primary w-100 add-cart my-5">ADD TO CART</a>
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
