@extends('layouts.app')

@section('title', 'My Orders')

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
          ['name' => 'My Order','url' => route('orders.index'),'active' => true,],
        ]
      ])
      @endside_menu

      <!-- Main Content-->
      <div class="col-sm w-100 mx-2">
        <!-- Content Header -->
        <h1 id="category-header" class="font-weight-bold p-2 border-bottom">
          My Order History
        </h1>
        <!-- End Content Header -->

        <!-- Content Body-->
        <div class="m-2">
          @data_table(['table_id' => 'table_list'])
            @slot('head')
              <tr>
                <th scope="col">#</th>
                <th scope="col">Order Date</th>
                <th scope="col">Order No</th>
                <th scope="col">Order Total</th>
                <th scope="col">Status</th>
                <th></th>
              </tr>
            @endslot

            @foreach ($orders as $index => $order)
              <tr class="cart-item">
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $order->order_date->toDayDateTimeString() }}</td>
                <td>{{ $order->order_no }}</td>
                <td>{{ number_format($order->total,2) }}</td>
                <td>{{ App\Model\Order::getStatus($order->status, false) }}</td>
                <td>
                  <a type="button" class="text-decoration-none text-body btn btn-sm btn-info"
                    href="{{ route('orders.show', ['order' => $order]) }}"
                  >
                    view Details
                </td>
              </tr>
            @endforeach
          @enddata_table
        </div>


        <!-- <div class="m-2"  id="content-row">
          @if($orders->count() == 0)
            <div class="p-2 mb-2">
              <p>No orders have been made so far.</p>
            </div>
          @endif

          @foreach ($orders as $index => $order)
            <div class="card p-2 mb-2 cart-item">
              <a class="text-decoration-none text-body" href="{{ route('orders.show', ['order' => $order]) }}">
                <h5 class="border-bottom p-2">
                  <strong>Order Number: </strong> {{ $order->order_no }}
                </h5>
              </a>

              {{ $index + 1 }}
            </div>
          @endforeach -->
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
  @include('shared.cart_item_hover_js')

  <script type="text/javascript">
    var table;          //datatable
    var selected_row;   //selected table row
  </script>
@endsection
