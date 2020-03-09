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
      <!-- Side Menu -->
      <div class="col-sm-3">
        <div class="card sub-menu border-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item bg-secondary">
              <h4 class="font-weight-bold">Side Menu:</h4>
            </li>
            <button class="list-group-item list-group-item-action active">Items in Cart</button>
            <button class="list-group-item list-group-item-action">My Orders</button>
          </ul>
        </div>
      </div>
      <!-- End Side Menu -->

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
@endsection
