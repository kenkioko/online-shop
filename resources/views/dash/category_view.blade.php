@extends('layouts.dash')

@section('title', 'Categories')

@section('page_header', ucwords($category->name))

@section('sidebar')
  @dash_sidebar(['page' => 'categories'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dash') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('admin.categories.index') }}">Categories</a>
    </li>
    <li class="breadcrumb-item active">{{ $category->name }}</li>
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')


    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @canany(['categories.create', 'categories.update'])
          <a type="button"
            href="{{ route('admin.categories.edit', ['category' => $category]) }}"
            class="btn btn-sm btn-outline-info pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Edit category"
          ><i class="nav-icon fas fa-edit"></i></a><!-- /.button -->
          @endcanany

          @can('categories.delete')
          <button type="button"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Delete category"
            onclick="on_delete()"
          ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endcan

          <a type="button"
            href="{{ route('admin.categories.index') }}"
            class="btn btn-sm btn-outline-secondary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Discard changes"
          ><i class="nav-icon fas fa-undo-alt"></i></a><!-- /.button -->
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        <div class="border-bottom mb-3">
          <h3> <strong>Category Name:</strong> {{ $category->name }}</h3>

          @php
            $parent_category = $category->parent_category()->first();
          @endphp

          @if($parent_category)
            <a href="{{ route('admin.categories.show', ['category' => $parent_category]) }}"
              class="text-decoration-none text-body"
            ><h5>
              <strong>Parent Name:</strong>
              <span class="badge badge-pill badge-primary py-2 px-3 m-1 ">{{ $parent_category->name }}</span>
            </h5></a>
          @endif
        </div>

        <!-- // sub categories -->
        @if($sub_categories->count() > 0)
          <div class="mb-3">
            <h5>Sub categories:</h5>
            @foreach ($sub_categories as $index => $sub_category)
              <a href="{{ route('admin.categories.show', ['category' => $sub_category]) }}"
                class="text-decoration-none text-body"
              ><span class="badge badge-pill badge-primary py-2 px-3 m-1 ">{{ $sub_category->name }}</span></a>
            @endforeach
          </div>
        @endif

        <!-- // items in category -->
        <h5>Items in category:</h5>
        <hr>
        @data_table(['table_id' => 'table_list'])
          @slot('head')
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Stock</th>
              <th scope="col">Price</th>
              <th scope="col">Discount</th>
              <th scope="col">Seller Name</th>
            </tr>
          @endslot

          @foreach ($items as $index => $item)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $item->name }}</td>
              <td>{{ $item->stock }}</td>
              <td>{{ number_format($item->price, 2) }}</td>
              <td>{{ $item->discount ?? 0 }}</td>
              <td>{{ $item->shop()->firstOrFail()->name }}</td>
            </tr>
          @endforeach
        @enddata_table

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @modal(['modal_id' => 'delete_modal'])
      @slot('modal_title')
        Delete '{{ $category->name }}'
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to delete '{{ $category->name }}'.</p>
        <form method="post" class="d-none"
          id="delete_category_form"
          action="{{ route('admin.categories.destroy', [
            'category' => $category
          ]) }}"
        >
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="delete_category_form">Delete</button>
      @endslot
    @endmodal

  </div>
@endsection

@section('page_js')
  @parent

  <script type="text/javascript">
    function on_delete() {
      $('#delete_modal').modal('show');
    }
  </script>
@endsection
