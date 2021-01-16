@extends('layouts.app')

@section('title')
  {{ $page_title }}
@endsection

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
  @endbreadcrum()

  <!-- Main Content-->
  <div class="container my-5">    

    <!-- Content Header -->
    <div id="content-header" class="p-4">
      <h3 class="text-center">
        {{ count($items) }} results from items in
      </h3>
      <h3 class="font-weight-bold text-center">
        "{{ $page_title }}"
      </h3>
    </div>        
    <!-- End Content Header -->

    <!-- Content Body-->
    <div id="content-body">

      <!-- items display -->
      @include('shared.item_display', [
        'items' => $items,
      ])
      <!-- End items display -->          

    </div>
    <!-- End Content Body-->

  </div>
  <!-- End Main Content-->
@endsection
