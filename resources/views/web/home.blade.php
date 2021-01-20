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
          <a id="products_tab_link" class="nav-link active" aria-current="page" href="javascript:void(0)">
            <h5><b>NEW PRODUCTS</b></h5>
          </a>
        </li>
        <li class="nav-item px-4">
          <a id="services_tab_link" class="nav-link" href="javascript:void(0)">
            <h5><b>NEW SERVICES</b></h5>
          </a>
        </li>
      </ul>

      @for ($i=0; $i < config('items.homepage_rows'); $i++)
        @if (count($products) > (config('items.items_in_row') * $i))
          <!-- products page row {{ $i }} -->
          <div id="row-{{ $i }}">

            <!-- product display -->
            <div class="d-none products-tab">
              @include('shared.item_display', [
                'items' => $products->splice(($i * config('items.items_in_row')), config('items.items_in_row'))
              ])
            </div>
            <!-- End product display -->

            <!-- product adverts -->
            <div class="products-advert">
              <!-- Big ads grid -->
              @include('shared.grid', [
                "cell_class" => "w-50",
                "img_style" => "height: 25rem;",
              ])
              <!-- End Big ads grid -->
            </div> 

          </div>
          <!-- End products page row {{ $i }} --> 
        @endif   
      @endfor 


      @for ($i=0; $i < config('items.homepage_rows'); $i++)
        @if (count($services) > (config('items.items_in_row') * $i))
          <!-- services page row {{ $i }} -->
          <div id="row-{{ $i }}">

            <!-- service display -->
            <div class="d-none services-tab">
              @include('shared.item_display', [
                'items' => $services->splice(($i * config('items.items_in_row')), config('items.items_in_row'))
              ])
            </div>
            <!-- End service display -->

            <!-- service adverts -->
            <div class="services-advert">
              <!-- Big ads grid -->
              @include('shared.grid', [
                "cell_class" => "w-50",
                "img_style" => "height: 25rem;",
              ])
              <!-- End Big ads grid -->
            </div> 

          </div>
          <!-- End services page row {{ $i }} --> 
        @endif               
      @endfor    
      
    </div>
  </div>
  <!-- Main Content -->
@endsection

@section('page_js')
  @parent

  <!-- Flickity JavaScript -->
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script src="{{ asset('/js/flikty.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
    var display_page;

    $(function () {
      function display_none() {
        $('#products_tab_link').removeClass('active');
        $('#services_tab_link').removeClass('active');   
        
        $('.products-tab').addClass('d-none');
        $('.services-tab').addClass('d-none');

        $('.products-advert').addClass('d-none');
        $('.services-advert').addClass('d-none');        
      }

      function display_products() {
        $('#products_tab_link').addClass('active');                
        $('.products-tab').removeClass('d-none');
        $('.products-advert').removeClass('d-none');
      } 

      function display_services() {
        $('#services_tab_link').addClass('active');        
        $('.services-tab').removeClass('d-none');
        $('.services-advert').removeClass('d-none');
      } 

      function display_items(type = null) {
        display_none();

        switch(type) {
          case 'products':
            display_products();
            break;
          case 'services':
            display_services();
            break;
          default:
            display_products();
        }
      }

      $('#products_tab_link').click(function () {
        display_items('products');
      });

      $('#services_tab_link').click(function () {
        display_items('services');
      });

      display_page = display_items;
    });

    $( window ).on( "load", function() {
      display_page();
    });
  </script>
@endsection
