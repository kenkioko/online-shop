@extends('layouts.app')

@section('title')
  @php
    echo 'Item &bull; ' .ucwords($item->name);
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

    @include('shared.show_alert')


    <!-- Item Description -->
    <div class="row">
      <!-- Item Images -->
      <div class="col-sm-6 d-flex flex-column">
        <img id="main_img" class="img-thumbnail shadow w-100"
          src=@if(count($files) == 0) "https://via.placeholder.com/500x350" @else "" @endif
          style="height: 60vh !important;"
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
          <a href="{{ route('shops.show', ['shop' => $shop]) }}">
            <p> {{ $shop->name }} </p>
          </a>
        </div>

        <div class="mt-2">
          <h4>Price: </h4>
          <p>
            @show_item_price(['item' => $item])
            @endshow_item_price
          </p>
        </div>

        @php
          $form_action = route('cart.store');
          $order_number = null;
          $edited_item = null;      // item to be edited if in the cart

          if($active_order) {
            $form_action = route('cart.update', ['cart' => $active_order]);
            $order_number = $active_order->order_no;
            $edited_item = $active_order->items()->find($item->id);
          }
        @endphp

        <form class="d-none" method="post" id="add_item_form" action="{{ $form_action }}">
          @csrf

          @if($active_order)
            @method('PUT')
          @endif

          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <input type="hidden" name="order_number" value="{{ $order_number }}">
          <input type="hidden" name="update_type" value="add" id="item_update_type">
        </form>

        @canany(['cart.create','cart.update'])
          <hr>
          @if($edited_item)
            <small class="muted">
              * This item is added to your cart.
              You can change the quantity you want to buy.
            </small>
          @endif

          <div class="d-flex flex-row my-3">
            <div class="d-flex align-items-center">
              <p class="mb-0 mr-2"> QTY: </p>

              @if($item->stock > 0)
                <input id="item_qty" type="number" form="add_item_form" name="quantity" min="1", max="{{ $item->stock }}"
                  value="{{ old('quantity', $edited_item->pivot->quantity ?? 1) }}"

                  @if($edited_item)
                    onchange="edit_type_update(
                      {{ old('quantity', 1) }},
                      {{ $item->price }},
                      'item_qty', 'item_total', 'item_update_type',
                      {{ $item->discount_amount ?? 0 }}
                    )"
                  @else
                    onchange="edit_type(
                      {{ old('quantity', 1) }},
                      {{ $item->price }},
                      'item_qty', 'item_total',
                      {{ $item->discount_amount ?? 0 }}
                    )"
                  @endif
                >
              @else
                <span>OUT OF STOCK</span>
              @endif
            </div>

            @if($item->stock > 0)
              <div class="d-flex align-items-center ml-auto">
                <p class="mb-0">
                  <strong>Total: </strong>

                  <span id="item_total">
                    @if($edited_item)
                      {{ number_format(($item->price - $item->discount_amount) * $edited_item->pivot->quantity, 2) }}
                    @else
                      {{ number_format($item->price, 2) }}
                    @endif
                  </span>
                </p>
              </div>
            </div>

            <div class="d-flex">
              <button type="submit" form="add_item_form" class="btn btn-primary ml-auto">
                ADD TO CART
              </button>
            </div>
          @endif
        @endcanany

        @guest
          <button type="submit" form="add_item_form" class="btn btn-outline-primary ml-auto font-weight-bold">
            LOGIN TO PURCHASE ITEM
          </button>
        @endguest
      </div>
    </div>
    <!-- End Item Description -->

    <hr>

    <!-- More Items Grid -->
    <!-- <div class="d-flex flex-column mt-5">
      <h3 id="category-header" class="mx-auto">You might also like</h3>

      <div class="grid-container" id="more-items-grid">
        @for($i = 0; $i < 10; $i++)
          <div class="grid-item">
            <img src="https://via.placeholder.com/150x150"/>
            <p>Item {{ $i + 1 }}</p>
          </div>
        @endfor
    </div> -->
    <!-- More Items Grid -->

  </div>
@endsection

@section('page_js')
  @parent

  <!-- Flickity JavaScript -->
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script src="{{ asset('/js/flikty.js') }}" type="text/javascript"></script>

  @if(count($files) > 0)
    <script type="text/javascript">
      function view_image(url) {
        var image = document.getElementById("main_img").src = url;
      }

      $(function(){
        view_image(first_img_url);
      });
    </script>
  @endif

  @include('shared.change_price_js')
@endsection
