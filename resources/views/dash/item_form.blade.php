@extends('layouts.dash')

@section('title', 'Items')

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
      <a href="{{ route('admin.dash') }}">Dashboard</a>
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

    @include('shared.show_alert')


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
              class="btn btn-sm btn-outline-danger pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Delete item"
              onclick="on_delete()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
            @endcan
          @endif


          <a type="button"
            href="{{ route('admin.items.index') }}"
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
              value=@if (old('name')) "{{ old('name') }}" @elseif ($item) "{{ $item->name }}" @else "" @endif
            >
          </div><!-- /.form-group -->

          <div class="form-group">
            <label>Select a type:</label>
            <select class="custom-select" name="type" id="type_input">
              <option @if (!($item and $item->type) or !old('type')) selected @endif>
                Choose A Type
              </option>

              @php
                $types = App\Models\Item::TYPE;
              @endphp

              @foreach ($types as $key => $type)
                <option @if ((old('type') == $key) or ($item and ($item->type == $key))) selected  @endif
                  value="{{ $key }}"
                >{{ $type }}</option>
              @endforeach
            </select>
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
            <label>Select a category:</label>
            <select class="custom-select" name="category_id">
              <option @if (!($item and $item->category) or !old('category_id')) selected @endif>
                Choose A Category
              </option>

              @php
                $categories = App\Models\Category::all();
              @endphp

              @foreach ($categories as $cat)
                <option @if ((old('category_id') == $cat->id) or ($item and ($item->category->id === $cat->id))) selected  @endif
                  value="{{ $cat->id }}"
                >{{ $cat->name }}</option>
              @endforeach
            </select>
          </div><!-- /.form-group -->

          <div class="row">
            <div class="form-group col">
              <label for="price_input">Original Price:</label>
              <input type="text"
                id="price_input"
                name="price"
                class="form-control"
                placeholder="Enter the item's price"
                value=@if (old('price')) "{{ old('price') }}" @elseif ($item) "{{ $item->price }}" @else "" @endif
                onchange="change_price_js()"
              >
            </div><!-- /.form-group -->

            <div class="form-group col">
              <label for="discount_percent_input">Discount percent (&percnt;):</label>
              <input type="number" min="0" max="99"
                id="discount_percent_input"
                name="discount_percent"
                class="form-control"
                placeholder="Enter Discount (&percnt;)"
                value=@if (old('discount_percent')) "{{ old('discount_percent') }}"
                  @elseif ($item)
                    "{{ $item->discount_percent ?? 0 }}"
                  @else "" @endif
                onchange="calculate_discount()"
              >
            </div><!-- /.form-group -->

            <div class="form-group col">
              <label for="discount_amount_input">Discount amount:</label>
              <input disabled type="text"
                id="discount_amount_input"
                class="form-control"
                name="discount_amount"
                placeholder="The discounted amount"
                value=@if (old('discount_amount')) "{{ old('discount_amount') }}"
                  @elseif ($item)
                    "{{ number_format($item->discount_amount, 2) ?? 0.00 }}"
                  @else "" @endif
              >
            </div><!-- /.form-group -->

            <div class="form-group col">
              <label for="discounted_price_input">Discounted Price:</label>
              <input disabled type="text"
                id="discounted_price_input"
                class="form-control"
                name="discounted_price"
                placeholder="Amount after discount"
                value=@if (old('discounted_price')) "{{ old('discounted_price') }}"
                  @elseif ($item)
                    "{{ number_format(($item->price - $item->discount_amount), 2) }}"
                  @else "" @endif
              >
            </div><!-- /.form-group -->
          </div><!-- /.row -->

          <div class="form-group" id="stock_form_group">
            <label for="stock_input">Remaining in Stock:</label>
            <input type="text"
              id="stock_input"
              name="stock"
              class="form-control"
              placeholder="Enter no of item's remaining in stock"
              value=@if (old('stock')) "{{ old('stock') }}" @elseif ($item) "{{ $item->stock }}" @else "" @endif
            >
          </div><!-- /.form-group -->

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
  @include('shared.format_number_js')

  <script type="text/javascript">
    $(function(){
      function show_stock(params) {
        var item_type = $('#type_input').val();
         if (item_type == 'product') {
           $('#stock_form_group').removeClass('d-none');
         } else if (item_type == 'service') {
          $('#stock_form_group').addClass('d-none');
         }
      }

      $('#type_input').change(function () {
        show_stock();
      })      

      show_stock();
    });
  </script>

  <script type="text/javascript">
    function on_delete() {
      $('#delete_modal').modal('show');
    }

    function change_price_js() {
      var price = $('#price_input').val();

      // set values
      $('#discounted_price_input').val(format_number(price));
      $('#discount_amount_input').val(0.00);
      $('#discount_percent_input').val(0);
    }

    function calculate_discount() {
      var discount_percent = $('#discount_percent_input').val();
      var price = $('#price_input').val();
      // calculate
      var discount_amount = price * (discount_percent / 100);
      // set values
      $('#discount_amount_input').val(format_number(discount_amount));
      $('#discounted_price_input').val(format_number(price - discount_amount));
    }

    function format() {

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

        $(form).submit(); //Change to use ajax for image file filters
      });
    });
  </script>
@endsection
