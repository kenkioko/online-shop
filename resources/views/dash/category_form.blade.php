@extends('layouts.dash')

@section('title', 'Categories')

@section('page_header')
  @if ($category)
    {{ ucwords($category->name) }}
  @else
    New Category
  @endif
@endsection

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
    @if ($category)
      <li class="breadcrumb-item active">{{ ucwords($category->name) }}</li>
    @else
      <li class="breadcrumb-item active">New</li>
    @endif
  @endbreadcrum()
@endsection

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')


    <div class="card">
      <div class="card-header">
        <div class="card-tools">

          @can('categories.view')
          @if ($category)
          <a type="button"
            href="{{ route('admin.categories.show', ['category' => $category]) }}"
            class="btn btn-sm btn-outline-primary pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="View category"
          ><i class="nav-icon far fa-eye"></i></a><!-- /.button -->
          @endif
          @endcan

          @canany(['categories.create', 'categories.update'])
          <button type="submit"
            form="category_form"
            class="btn btn-sm btn-outline-success pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Save category"
          ><i class="nav-icon fas fa-save"></i></button><!-- /.button -->
          @endcanany

          @can('categories.delete')
          @if ($category)
            <button type="button"
              class="btn btn-sm btn-outline-danger pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Delete category"
              onclick="on_delete()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endif
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

        @php
          $form_action = route('admin.categories.store');
          if ($category) {
            $form_action = route('admin.categories.update', [
              'category' => $category
            ]);
          }
        @endphp

        <form action="{{ $form_action }}" method="post" id="category_form">
          @csrf

          @if ($category)
            @method('PUT')
          @endif

          <div class="form-group">
            <label for="name_input">Name:</label>
            <input type="text"
              id="name_input"
              name="name"
              class="form-control"
              placeholder="Enter the category's name"

              @if (old('name'))
                value="{{ old('name') }}"
              @elseif ($category)
                value="{{ $category->name }}"
              @endif
            >
          </div><!-- /.form-group -->
          <div class="form-group">
            <label>Select a parent category:</label>
            <select class="custom-select" name="parent_category_id">
              @if (($category and $category->parent_category) or old('parent_category_id'))
                <option value="">No parent</option>
              @else
                <option selected value="">No parent</option>
              @endif

              @foreach ($categories as $cat)
                @if (old('parent_category_id') == $cat->id)
                  <option value="{{ $cat->id }}" selected>{{ $cat->name }}</option>
                @elseif ($category and $category->parent_category == $cat)
                  <option value="{{ $cat->id }}" selected>{{ $cat->name }}</option>
                @else
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endif
              @endforeach
            </select>
          </div><!-- /.form-group -->
        </form>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @if ($category)

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

    @endif

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
