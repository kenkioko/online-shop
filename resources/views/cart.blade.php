@extends('layouts.app')

@section('title')
  something
@endsection

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">something</li>
  @endbreadcrum()

  <div class="container my-5 d-flex">

    <div class="row w-100">
      <!-- Side Menu -->
      <div class="col-sm-3">
        <div class="card sub-menu border-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <h4 class="font-weight-bold">Filter By</h4>
              </li>
            <li class="list-group-item">Brand</li>
            <li class="list-group-item">Size</li>
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
