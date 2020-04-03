@extends('layouts.app')

@section('title', 'Cart')

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Items in Cart</li>
  @endbreadcrum()

  <div class="container">
    @show_alert(['errors', $errors])
    @endshow_alert
  </div>

  <div class="container my-5 d-flex">

    <div class="row w-100">
      @side_menu([
        'title' => 'SIDE MENU',
        'menu_items' => [
          ['name' => 'Items in Cart','url' => route('cart.index'),'active' => true],
          ['name' => 'My Orders','url' => route('orders.index')],
        ]
      ])
      @endside_menu

      <!-- Main Content-->
      <div class="col-sm w-100 mx-2">
        <!-- Content Header -->
        <div id="category-header" class="p-2 border-bottom d-flex align-items-end">
          <h1  class="font-weight-bold">
            Items In Cart
          </h1>

          <p class="ml-auto">
            <strong>Order No:</strong>
            <span class="text-muted">{{ $cart->order_no }}</span>
          </p>
        </div>
        <!-- End Content Header -->

        <!-- Cart Body-->
        <div class="m-2 p-2 border-bottom"  id="content-row">
          @if($cart->items()->count() == 0)
            <div class="p-2 mb-2">
              <p>No items in cart.</p>
            </div>
          @endif

          @foreach($cart->items()->get() as $index => $item)
            <div class="card p-2 mb-2 d-flex flex-row align-items-center cart-item">

              <a class="text-decoration-none" href="{{ route('items.show', ['item' => $item]) }}">
                <div class=" d-flex flex-row">
                  <img class="shadow m-2 rounded-pill" src="https://via.placeholder.com/100x70"/>

                  <div class="text-body ml-4 p-2 border-left">
                    <h5 class="border-bottom"> <strong>{{ $item->name }}</strong> </h6>
                    <p>
                      <strong>Price:</strong>
                      @show_item_price(['item' => $item])
                      @endshow_item_price
                    </p>
                  </div>
                </div>
              </a>

              <div class="ml-auto p-2 border-left">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">QTY:</span>
                  </div>

                  <form id="item{{$index}}_form" class="d-none" action="{{ route('cart.update', ['cart' => $cart]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="order_number" value="{{ $cart->order_no }}">
                    <input type="hidden" name="update_type" value="edit">
                  </form>
                  <input type="number" class="form-control" min="1" max="{{ $item->stock }}"
                    id="item{{$index}}_qty"
                    form="item{{$index}}_form"
                    name="quantity" value="{{ $item->pivot->quantity }}"
                    onchange="edit_type(
                      {{ $item->pivot->quantity }},
                      {{ $item->price }},
                      'item{{$index}}_qty',
                      'item{{$index}}_disp',
                      {{ $item->discount_amount ?? 0 }}
                    )"
                  >

                  <div class="input-group-append">
                    <button type="submit" form="item{{$index}}_form" class="input-group-text">
                      save
                    </button>
                  </div>
                </div>

                <p>
                  <strong>Total:</strong>
                  <span id="item{{$index}}_disp">
                    {{ number_format($item->pivot->amount, 2) }}
                  </span>
                </p>
              </div>


              <form id="delete_item_form" action="{{ route('cart.destroy', ['cart' => $item]) }}" method="post">
                @csrf
                @method('DELETE')
              </form>

              <button type="button" class="ml-auto mb-auto p-2 btn btn-danger close" onclick="removeItem('{{ $item->name }}')"
                data-toggle="modal" data-target="#confirm_delete_modal"
              ><span aria-hidden="true">&times;</span>  </button>
            </div><!-- /.card -->
          @endforeach

        <!-- End Cart Body-->
        </div>

        <!-- Cart Body-->
        <div class="p-2 d-flex flex-row align-items-center">

          @if($cart->items()->count() > 0)
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#complete_order_modal">
              Complete Order
            </button>
          @endif

          <h4 class="ml-auto">
            <strong>Grand Total:</strong>
            <span class="text-muted ml-2"> {{ number_format($cart->total, 2) }}</span>
          </h4>
        </div>
      <!-- End Main Content-->
      </div>

    </div>
  </div>

  <!-- remove item from cart confirmation modal -->
  @modal([
    'modal_id' => 'confirm_delete_modal',
    'modal_title' => 'Remove From Cart',
    'modal_class' => 'modal-dialog-centered',
    'modal_header_class' => 'bg-danger'
  ])

    @slot('modal_body')
      Are you sure you want to delete '<span id="confirm_item_name"></span>' from the cart?
    @endslot

    @slot('modal_footer')
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      <button type="submit" form="delete_item_form" class="btn btn-outline-danger">OK</button>
    @endslot
  @endmodal


  <!-- complete order confirmation modal -->
  @modal([
    'modal_id' => 'complete_order_modal',
    'modal_title' => 'Complete Order',
    'modal_class' => 'modal-dialog-centered',
    'modal_header_class' => 'bg-success'
  ])

    @slot('modal_body')
      Are you sure you want to complete the order?

      <form id="confirm_order_form" class="d-none" action="{{ route('orders.store') }}" method="post">
        @csrf
        <input type="hidden" name="order_number" value="{{ $cart->order_no }}">
      </form>
    @endslot

    @slot('modal_footer')
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      <button type="submit" form="confirm_order_form" class="btn btn-outline-success">YES</button>
    @endslot
  @endmodal

@endsection

@section('page_js')
  @parent

  <script type="text/javascript">
    function removeItem(name) {
      $('#confirm_item_name').text(name);
    }
  </script>

  @include('shared.cart_item_hover_js')
  @include('shared.change_price_js')
@endsection
