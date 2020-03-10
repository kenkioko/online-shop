@extends('layouts.app')

@section('title')
  My Orders
@endsection

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
          Header
        </h1>
        <!-- End Content Header -->

        <!-- Content Body-->
        <div class="card-columns m-2"  id="content-row">
          Body
        <!-- End Content Body-->
        </div>
      <!-- End Main Content-->
      </div>

    </div>
    <!-- /.row-->

  </div>
@endsection
