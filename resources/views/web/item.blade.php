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
          <span class="font-weight-bold">Price:</span><br>
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
          <input type="hidden" name="delivery_address" id="delivery_address_input">
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
              @if ($item->bid_allowed)
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_address_modal" 
                  onclick="orderType('bid')"
                >HIGHEST BIDDER</button>
              @endif

              <button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#add_address_modal"
                onclick="orderType('buy')"
              >BUY ITEM</button>
            </div>
          @endif
        @endcanany

        @guest
          <button type="button" class="btn btn-outline-primary ml-auto font-weight-bold">
            LOGIN TO PURCHASE ITEM
          </button>
        @endguest
      </div>
    </div>
    <!-- End Item Description -->

    <hr>

    <!-- More Items Grid -->
    <div class="services-advert">
      <!-- Big ads grid -->
      @include('shared.grid', [
        "cell_class" => "w-50",
        "img_style" => "height: 25rem;",
      ])
      <!-- End Big ads grid -->
    </div> 
    <!-- End More Items Grid -->

    <!-- Related Items -->
    <div>
      <h3><b>Similar Products</b></h3>

      @include('shared.item_display', [
        'items' => $related_items,
      ])
    </div>
    <!-- End Related Items -->

  </div>

  <!-- Add Address Item Modal -->
  @modal([
    'modal_id' => 'add_address_modal',
    'modal_header_class' => 'border-0 pb-0',
    'modal_title_class' => 'h3 ml-auto pt-0',
    'modal_footer_class' => 'border-0',
  ])
    @slot('modal_title')
      <b>Shipping Address</b>
    @endslot

    @slot('modal_body')
      <p class="text-center">
        Select your preferred shipping address so that your package can be droped there.
      </p>

      <div class="my-4">
        @foreach($delivery_addresses as $delivery_address)
          <div class="border p-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="select_address" 
                id="delivery_address_input" 
                value="{{ $delivery_address }}" 
                @if($delivery_address->primary_address) checked @endif
              >
              <label class="form-check-label" for="delivery_address_input">
                <p class='m-0'>{{ $delivery_address->full_address }}</p>
              </label>
            </div>            
          </div>     
        @endforeach
      </div>      
    @endslot

    @slot('modal_footer')
      <a type="button" class="btn btn-outline-primary" href="{{ route('profile.index') }}">
        ADD SHIPPING ADDRESS
      </a>

      <button type="button" class="btn btn-primary ml-2" data-dismiss="modal" id="select_address_btn">
        PROCEED
      </button>       
    @endslot
  @endmodal


  @if ($item->bid_allowed)
    <!-- Bid Item Modal -->
    @modal([
      'modal_id' => 'bid_item_modal',
      'modal_header_class' => 'border-0 pb-0',
      'modal_title_class' => 'h3 ml-auto pt-0',
      'modal_footer_class' => 'border-0',
    ])
      @slot('modal_title')
        <b>Product Bidding</b>
      @endslot

      @slot('modal_body')
        <p class="text-center">
          The price you will place on the product will be public for everyone to see. Your name won’t be visible though.
        </p>

        <form id="bid_item_form" action="{{ route('orders.bid') }}" method="post">
          @csrf
          
          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <input type="hidden" name="delivery_address" id="bid_address_input">

          <div class="row my-4">
            <div class="col-sm-6">
              <p><b>Starting time</b></p>
              <h3><b>08:30 am</b></h3>
            </div>

            <div class="col-sm-6">
              <p><b>Stoppage time</b></p>
              <h3><b>12:30 pm</b></h3>
            </div>
          </div>

          <div class="form-group">
            <label for="bid_amount_input">Amount</label>
            <input type="email" class="form-control bg-light" name="amount" id="bid_amount_input" placeholder="Minimum amount: Ksh 90,000">
          </div>
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          CANCEL
        </button> 

        <button type="submit" form="bid_item_form" class="btn btn-primary ml-2">
          SUBMIT YOUR BID
        </button>     
      @endslot
    @endmodal
  @endif


  @if ($item->trade_allowed)
    <!-- Bid Item Modal -->
    @modal([
      'modal_id' => 'buy_item_modal',
      'modal_header_class' => 'border-0 pb-0',
      'modal_title_class' => 'h3 ml-auto pt-0',
      'modal_footer_class' => 'border-0',
    ])
      @slot('modal_title')
        <b>Buy or Trade-in</b>
      @endslot

      @slot('modal_body')        
        <p class="text-center">
          The owner of this item accepts a trade in, you can either buy item or trade another item.
        </p>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
          ADVANCE TO TRADE IN
        </button> 

        <button type="submit" form="add_item_form" class="btn btn-primary ml-2">
          ADD TO CART
        </button>    
      @endslot
    @endmodal
  @endif
@endsection

@section('page_js')
  @parent

  <!-- Flickity JavaScript -->
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script src="{{ asset('/js/flikty.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
    var order_type;

    function orderType(type) {
      order_type = type;
    }
  </script>

  <script type="text/javascript">
    $(function () {
      $('#select_address_btn').click(function () {
        address = $("input[name=select_address]").val();

        // set delivery address
        $('#delivery_address_input').val(address);
        $('#bid_address_input').val(address);

        if (order_type == 'bid') {
          $('#bid_item_modal').modal('show');
        } 
        
        @if($item->trade_allowed)
          if (order_type == 'buy') {
            $('#buy_item_modal').modal('show');
          }
        @endif

        @if(!$item->trade_allowed)
          if (order_type == 'buy') {
            $('#add_item_form').submit();
          }
        @endif
      });
    });
  </script>  

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
