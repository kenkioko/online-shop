<div class="row row-cols-1 row-cols-md-{{ $row_cols ?? 4 }} my-5">
  @foreach($items as $item)

  @php
    $url = 'https://via.placeholder.com/500x500';
    $directory = 'public/item_images/' . $item->images_folder;
    $image_files = Storage::files($directory);

    if($image_files){
      $url = asset(Illuminate\Support\Facades\Storage::url($image_files[0]));
    }
  @endphp

  <div class="col-3 my-3">
    <a href="{{ route('items.show',['item' => $item->id]) }}" class="text-dark text-decoration-none">
      <!-- Card -->
      <div class="card border-0 bg-light h-100">
        <img src="{{ $url }}" class="card-img-top" alt="...">
        <div class="card-body p-1">
          <p class="card-text">{{ $item->name }}</p>
          <p>KSH {{ number_format($item->price, 2) }}</p>
        </div>
      </div>
      <!-- End Card -->
    </a>
  </div>
  @endforeach
</div>