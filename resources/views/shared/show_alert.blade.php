<!-- alerts -->
<div id="page_alert">
  @if ($errors->any() or session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>

      @if (session('error'))
        <p class="m-0">{{ session('error') }}</p>
      @endif

      @if ($errors->any())
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      @endif

    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <p class="m-0">{{ session('success') }}</p>
    </div>
  @endif
</div>
<!-- alerts -->
