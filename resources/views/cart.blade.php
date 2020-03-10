@extends('layouts.app')

@section('title')
  Cart
@endsection

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Items in Cart</li>
  @endbreadcrum()

  <div class="container my-5 d-flex">

    <div class="row w-100">
      @side_menu([
        'title' => 'SIDE MENU',
        'menu_items' => [
          ['name' => 'Items in Cart','url' => route('cart.index'),'active' => true],
          ['name' => 'My Order','url' => route('orders.index')],
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
          @foreach($cart->items()->get() as $item)

            <div class="card p-2 mb-2 d-flex flex-row align-items-center">
              <a class="text-decoration-none" href="{{ route('items.show', ['item' => $item]) }}">
                <img class="shadow m-2 rounded-pill" src="https://via.placeholder.com/100x70"/>
              </a>

              <a class="text-decoration-none" href="{{ route('items.show', ['item' => $item]) }}">
                <p class="ml-4 m-2 text-body">
                  <strong>{{ $item->name }}</strong> <br>

                  @show_item_price(['item' => $item])
                  @endshow_item_price
                </p>
              </a>

              <button type="button" class="ml-auto p-2 btn btn-danger close" onclick="removeItem('{{ $item->name }}')"
                data-toggle="modal" data-target="#confirm_delete_modal"
              ><span aria-hidden="true">&times;</span>  </button>
            </div><!-- /.card -->

          @endforeach
        <!-- End Cart Body-->
        </div>

        <!-- Cart Body-->
        <div class="p-2 d-flex flex-row align-items-center">
          <button type="button"class="btn btn-primary">Confirm Order</button>

          <h4 class="ml-auto">
            <strong>Total:</strong>
            <span class="text-muted ml-2"> {{ number_format($cart->total, 2) }}</span>
          </h4>
        </div>
      <!-- End Main Content-->
      </div>

    </div>
  </div>


  @modal([
    'modal_id' => 'confirm_delete_modal',
    'modal_title' => 'Remove From Cart'
  ])

    @slot('modal_body')
      <form id="delete_item_form" action="{{ route('cart.destroy', ['cart' => $cart]) }}" method="post">
        @csrf
        @method('DELETE')
      </form>
      Are you sure you want to delete '<span id="confirm_item_name"></span>' from the cart?
    @endslot

    @slot('modal_footer')
      <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
      <button type="submit" form="delete_item_form" class="btn btn-outline-light">OK</button>
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
@endsection
