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
      <a href="{{ route('categories.show',['category' => $item->category]) }}">
        {{ ucwords($item->category->name) }}
      </a>
    </li>
    <li class="breadcrumb-item active">{{ ucwords($item->name) }}</li>
  @endbreadcrum()

  <div class="container my-5">

    @show_alert(['errors', $errors])
    @endshow_alert

    <!-- Item Description -->
    <div class="row">
      <!-- Item Images -->
      <div class="col-sm-6 d-flex flex-column">
        <img id="main_img" class="img-thumbnail shadow w-100"
          src="https://via.placeholder.com/500x350"
          style="height: 60vh !important"
        />

        <div class="grid-container mt-auto" id="item-images-grid">
          @foreach($files as $file)
            @php
              $url = Illuminate\Support\Facades\Storage::url($file);
            @endphp

            @if ($loop->first)
              <script type="text/javascript">
                var first_img_url = '{{ asset($url) }}'
              </script>
            @endif

            <div class="grid-item"><img src="{{ asset($url) }}"
              onclick="view_image('{{ asset($url) }}')"
              width="100" height="100"
            /></div>
          @endforeach
        </div>

      </div>
      <!-- End Item Images -->

      <div class="col-sm-6 px-5">
        <h1 class="border-bottom" id="item-title">
          {{ ucwords($item->name) }}
        </h1>

        <p class="my-4">
          <span class="font-weight-bold">Description:</span><br>

          {{ ucwords($item->description) }}
        </p>

        <div class="mt-2">
          <span class="font-weight-bold">Seller:</span><br>
          <p> {{ $item->shop()->first()->name }} </p>
        </div>

        <div class="mt-2">
          <h4>Price: </h4>
          <p>
            @show_item_price(['item' => $item])
            @endshow_item_price
          </p>
        </div>

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

        @php
          $form_action = route('cart.store');
          $order_number = null;

          if($active_order) {
            $form_action = route('cart.update', ['cart' => $active_order]);
            $order_number = $active_order->order_no;
          }
        @endphp

        <form class="d-none" method="post"
          id="add_item_form"
          action="{{ $form_action }}"
        >
          @csrf

          @if($active_order)
            @method('PUT')
          @endif

          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <input type="hidden" name="order_number" value="{{ $order_number }}">
          <input type="hidden" name="update_type" value="add">
        </form>

        @auth
          <button type="submit" form="add_item_form" class="btn btn-primary my-5">
            ADD TO CART
          </button>
        @endauth

        @guest
          <a href="{{ route('login') }}" class="btn btn-primary my-5">
            LOGIN TO PURCHASE ITEM
          </a>
        @endguest
      </div>
    </div>
    <!-- End Item Description -->

    <hr>

    <!-- More Items Grid -->
    <div class="d-flex flex-column mt-5">
      <h3 id="category-header" class="mx-auto">You might also like</h3>

      <div class="grid-container" id="more-items-grid">
        @for($i = 0; $i < 10; $i++)
          <div class="grid-item">
            <img src="https://via.placeholder.com/150x150"/>
            <p>Item {{ $i + 1 }}</p>
          </div>
        @endfor
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
    function view_image(url) {
      document.getElementById("main_img").src = url;
    }

    $(function(){
      view_image(first_img_url);
    });
  </script>
@endsection
