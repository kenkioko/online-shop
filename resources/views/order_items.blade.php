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
        <div class="card m-2">
          <div class="card-body">

            <div class="d-flex flex-row">
              <!-- Order Details -->
              <table id="order_details">
                <tr>
                  <td><strong>Order No: </strong></td>
                  <td class="px-2">{{ $order->order_no }}</td>
                </tr>
                <tr>
                  <td><strong>Order Date: </strong></td>
                  <td class="px-2">{{ $order->order_date->toDayDateTimeString() }}</td>
                </tr>
                <tr>
                  <td><strong>Order Status: </strong></td>
                  <td class="px-2">{{ App\Model\Order::getStatus($order->status) }}</td>
                </tr>
                <tr>
                  <td><strong>Order Total: </strong></td>
                  <td class="px-2">{{ number_format($order->total, 2) }}</td>
                </tr>
              </table>


              <table id="Customer_details" class="ml-auto">
                <!-- Customer Details -->
                <tr>
                  <td><strong>Customer Name: </strong></td>
                  <td class="px-2">{{ $order->user()->first()->name }}</td>
                </tr>
              </table>
            </div><!-- /.d-flex -->

            <div class="border-top border-bottom mt-3">
              <h5>Order Details:</h5>
              @data_table(['table_id' => 'table_list'])
                @slot('head')
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Seller Name</th>
                    <th scope="col">Progress Status</th>
                  </tr>
                @endslot

                @foreach ($order->items()->get() as $index => $item)
                  <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                    <td>{{ $item->pivot->amount }}</td>
                    <td>{{ $item->shop()->first()->name }}</td>
                    <td>{{ App\Model\Item::getStatus($item->pivot->status, false) }}</td>
                  </tr>
                @endforeach
              @enddata_table
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->


        <!-- <div class="m-2"  id="content-row">
          @if($order->items()->count() == 0)
            <div class="p-2 mb-2">
              <p>No orders have been made so far.</p>
            </div>
          @endif

          @foreach ($order->items()->get() as $index => $item)
            <div class="card p-2 mb-2 cart-item">
              {{ $index + 1 }}. {{ $item->name }} {{ App\Model\Item::getStatus($item->pivot->status, false) }}
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
  @include('shared.cart_item_hover')
@endsection
