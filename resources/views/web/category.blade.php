@extends('layouts.app')

@section('title')
  @php
    echo 'Category &bull; ' . ucwords($category->name);
  @endphp
@endsection

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">{{ ucwords($category->name) }}</li>
  @endbreadcrum()

  <div class="container my-5 d-flex">

    <div class="row w-100">

      {{-- @side_menu([
        'title' => 'FITER BY',
        'menu_items' => [
          ['name' => 'Brand','url' => '#'],
          ['name' => 'Size','url' => '#'],
        ]
      ])
      @endside_menu --}}

      <!-- Main Content-->
      <div class="col-sm w-100 mx-2">
        <!-- Content Header -->
        <h1 id="category-header" class="font-weight-bold p-2 border-bottom">
          {{ ucwords($category->name) }}
        </h1>
        <!-- End Content Header -->

        <!-- Content Body-->
        <div class="card-columns m-2"  id="content-row">

          @foreach ($items as $item)

            @php
              $url = 'https://via.placeholder.com/150x150';
              $directory = 'public/item_images/' . $item->images_folder;
              $image_files = Storage::files($directory);

              if($image_files){
                $url = asset(Illuminate\Support\Facades\Storage::url($image_files[0]));
              }
            @endphp

            <!-- Card -->
            <div class="card shadow p-0">
              <a href="{{ route('items.show',['item' => $item->id]) }}">
                <img class="w-100" src="{{ $url }}"/>
              </a>
              <div class="card-body">
                <a href="{{ route('items.show',['item' => $item->id]) }}"
                  class="text-dark"
                >
                  <h5 class="card-title">{{ $item->name }}</h5>
                </a>
                <p class="card-text">Ksh. {{ number_format($item->price, 2) }}</p>
              </div>
            </div>
            <!-- End Card -->
          @endforeach

        </div>
        <!-- End Content Body-->
      </div>
      <!-- End Main Content-->
    </div>

  </div>
@endsection
