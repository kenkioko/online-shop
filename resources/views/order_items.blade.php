@extends('layouts.app')

@section('title', 'View Order')

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">My Orders and Items in Cart</li>
  @endbreadcrum()

  <div class="container my-5 d-flex">

    <div class="row w-100">
      @side_menu([
        'title' => 'SIDE MENU',
        'menu_items' => [
          ['name' => 'Items in Cart','url' => route('cart.index')],
          ['name' => 'My Orders','url' => route('orders.index')],
          ['name' => 'View Order','url' => '#','active' => true],
        ]
      ])
      @endside_menu

      <!-- Main Content-->
      <div class="col-sm w-100 mx-2">
        <!-- Content Header -->
        <div id="category-header" class="p-2 border-bottom d-flex flex-row align-items-end">
          <h1 class="font-weight-bold"> Order Number:</h1>
          <h4 class="text-muted ml-auto">{{ $order->order_no }}</h4>
        </div>
        <!-- End Content Header -->

        <!-- Content Body-->
        <div class="m-2"  id="content-row">
          @if($order->items()->count() == 0)
            <div class="p-2 mb-2">
              <p>No orders have been made so far.</p>
            </div>
          @endif

          @foreach ($order->items()->get() as $index => $item)
            <div class="card p-2 mb-2 cart-item">
              {{ $index + 1 }} {{ $item->name }}
            </div>
          @endforeach
        <!-- End Content Body-->
        </div>
      <!-- End Main Content-->
      </div>

    </div>
    <!-- /.row-->

  </div>
@endsection

@section('page_js')
  @parent
  @include('shared.cart_item_hover')
@endsection
