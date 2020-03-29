@extends('layouts.dash')

@section('page_header')
  @if ($item)
    {{ ucwords($item->name) }}
  @else
    New Item
  @endif
@endsection

@section('sidebar')
  @dash_sidebar(['page' => 'items'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dash') }}">Admin</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('admin.items.index') }}">Items</a>
    </li>
    @if ($item)
      <li class="breadcrumb-item active">{{ ucwords($item->name) }}</li>
    @else
      <li class="breadcrumb-item active">New</li>
    @endif
  @endbreadcrum()
@endsection

@php
  use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
  <div class="container-fluid">

    @show_alert(['errors', $errors])
    @endshow_alert

    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          @if ($item)
            @can('items.view')
            <a type="button"
              class="btn btn-sm btn-outline-primary pop"
              href="{{ route('items.show', ['item' => $item]) }}"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="View in site"
            ><i class="nav-icon far fa-eye"></i></a><!-- /.button -->
            @endcan
          @endif

          @canany(['items.create', 'items.update'])
          <button type="submit"
            form="item_form"
            class="btn btn-sm btn-outline-success pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Save changes"
          ><i class="nav-icon fas fa-save"></i></button><!-- /.button -->
          @endcanany

          @if ($item)
            @can('items.delete')
            <button type="button"
              class="btn btn-sm btn-outline-warning pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Delete item"
              onclick="on_delete()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
            @endcan
          @endif


          <a type="button"
            href="{{ route('admin.items.index') }}"
            class="btn btn-sm btn-outline-danger pop"
            data-container="body" data-toggle="popover" data-placement="bottom"
            data-content="Discard changes"
          ><i class="nav-icon fas fa-times-circle"></i></a><!-- /.button -->


        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        @php
          $form_action = route('admin.items.store');
          if ($item) {
            $form_action = route('admin.items.update', [
              'item' => $item
            ]);
          }
        @endphp

        <form action="{{ $form_action }}" method="post" id="item_form" enctype="multipart/form-data">
          @csrf

          @if ($item)
            @method('PUT')
          @endif

          <div class="form-group">
            <label for="name_input">Name:</label>
            <input type="text"
              id="name_input"
              name="name"
              class="form-control"
              placeholder="Enter the item's name"

              @if (old('name'))
                value="{{ old('name') }}"
              @elseif ($item)
                value="{{ $item->name }}"
              @endif
            >
          </div><!-- /.form-group -->
          <div class="form-group">
            @php
              $description_value = null;
              if (old('description')) {
                $description_value = old('description');
              } else if ($item) {
                $description_value = $item->description;
              }
            @endphp

            <label for="desription_input">Description:</label>
            <textarea
              rows="3"
              id="desription_input"
              name="description"
              class="form-control"
              placeholder="Short description for the item"
            >{{ $description_value }}</textarea>
          </div><!-- /.form-group -->
          <div class="form-group">
            <label>Select a parent item:</label>
            <select class="custom-select" name="category_id">
              @if (($item and $item->category) or old('description'))
                <option value="">Choose A Category</option>
              @else
                <option selected value="">Choose A Category</option>
              @endif

              @php
                $categories = App\Category::all();
              @endphp

              @foreach ($categories as $cat)
                @if (old('category_id') == $cat->id)
                  <option selected value="{{ $cat->id }}">{{ $cat->name }}</option>
                @elseif ($item and ($item->category->id === $cat->id))
                  <option selected value="{{ $cat->id }}">{{ $cat->name }}</option>
                @else
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endif
              @endforeach
            </select>
          </div><!-- /.form-group -->

          <div class="row">
            <div class="form-group col-6">
              <label for="price_input">Price:</label>
              <input type="text"
                rows="3"
                id="price_input"
                name="price"
                class="form-control"
                placeholder="Enter the item's price"

                @if (old('price'))
                  value="{{ old('price') }}"
                @elseif ($item)
                  value="{{ $item->price }}"
                @endif
              >
            </div><!-- /.form-group -->
            <div class="form-group col-6">
              <label for="stock_input">Remaining in Stock:</label>
              <input type="text"
                rows="3"
                id="stock_input"
                name="stock"
                class="form-control"
                placeholder="Enter no of item's remaining in stock"

                @if (old('stock'))
                  value="{{ old('stock') }}"
                @elseif ($item)
                  value="{{ $item->stock }}"
                @endif
              >
            </div><!-- /.form-group -->
          </div><!-- /.row -->

          <div class="form-group"><!-- /.form-group -->
            <label for="image_preview">Item images:</label>
            <div class="custom-file">
              <input multiple type="file" accept="image/*"
                id="item_images" name="images[]"
                class="custom-file-input"
              >
              <label class="custom-file-label" for="item_images" id="item_images_label">
                Choose Item Images
              </label>
            </div><!-- /.custom-file -->
            <div class="card-columns p-2 my-2" id="image_preview">
              <!-- Previev images -->
              @if($item)
                @foreach($files as $file)
                  @php
                    $url = Illuminate\Support\Facades\Storage::url($file);
                  @endphp

                  <div class="card"><img class="card-img-top"
                    src="{{ asset($url) }}"
                  ></div>
                @endforeach
              @endif
            </div>
          </div>

        </form>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @if ($item)

      @modal(['modal_id' => 'delete_modal'])
        @slot('modal_title')
          Delete '{{ $item->name }}'
        @endslot

        @slot('modal_body')
          <p>Are you sure you want to delete '{{ $item->name }}'.</p>
          <form method="post" class="d-none"
            id="delete_item_form"
            action="{{ route('admin.items.destroy', [
              'item' => $item
            ]) }}"
          >
            @csrf
            @method('DELETE')
          </form>
        @endslot

        @slot('modal_footer')
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" form="delete_item_form">Delete</button>
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

  <script type="text/javascript">
  $(function(){
    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
    var images = [];

    $('#item_images').change(function () {
      delete_previous();

      var length = 0;
      $.each( this.files, function( index, file ) {
        if (validImageTypes.includes(file.type)) {
          var url = window.URL.createObjectURL(file);
          create_card(url);

          images.push(file);
          length += 1;
        }
      });

      $('#item_images_label').text(
       length + ' image file(s) selected.'
      );

      console.log(images);
    });

    function delete_previous(files) {
      var preview = document.getElementById('image_preview');
      while(preview.firstChild){
        preview.removeChild(preview.firstChild);
      }
    }

    function create_card(url) {
      // card element
      var card = document.createElement('div');
      card.classList.add("card");

      // card image element
      var image = document.createElement('img');
      image.classList.add("card-img-top");
      image.src = url;

      //append
      card.appendChild(image);
      document.getElementById('image_preview').appendChild(card);
    }

    $('#submit_item_form').click(function (event) {
      let form = document.getElementById('item_form');
      let formData = new FormData(form);

      formData.delete('images[]');
      $.each( images, function( index, image ) {
        formData.append('images[]', image, image.name);
      });

      console.log(formData);
      $(form).submit(); //Change to use ajax for image file filters
    });
  });
  </script>
@endsection
