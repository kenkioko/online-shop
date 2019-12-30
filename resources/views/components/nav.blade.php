<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top d-flex">
  <a class="navbar-brand" href="{{ route('home.index') }}">
    <img src="{{ asset('/adminlte/dist/img/AdminLTELogo.png') }}" alt="Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: .8" height="40rem"
    >
    <span class="brand-text font-weight-light">My Shop</span>
  </a>
  <button type="button"
    class="navbar-toggler ml-auto"
    data-toggle="collapse"
    data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent"
    aria-expanded="false"
    aria-label="Toggle navigation"
  >
    <span class="navbar-toggler-icon"></span>
  </button>

  <span class="navbar-text p-1 order-3">
    <button class="btn btn-outline-light ml-5" type="button">CART</button>
  </span>

  <span class="navbar-text p-1 order-3">
    @php
      use Illuminate\Support\Facades\Auth;
      $user = Auth::user();
    @endphp

    <ul class="navbar-nav mr-5">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-light"
          id="navbarDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          @if ($user)
            {{ $user->name }}
          @else
            Login
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right " aria-labelledby="navbarDropdown">
          @if ($user)
            @if ($user->user_level === 'admin')
              <a class="dropdown-item text-dark" href="{{ route('admin.dash') }}">
                Admin Dashboard
              </a>
            @endif

            @logout(['display' => 'text'])
              <!--print logout button -->
            @endlogout
          @else
            <a class="dropdown-item text-dark" href="{{ route('login') }}">
              Login
            </a>
            <a class="dropdown-item text-dark" href="{{ route('register') }}">
              Register
            </a>
          @endif
        </div>
      </li>
    </ul>
  </span>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-5">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-light"
          id="navbarDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >Categories</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <!-- List all categories -->
          @php
            $categories = App\Category::all();
          @endphp

          @foreach ($categories as $category)
            <a class="dropdown-item"
              href="{{ route('categories.show', ['category' => $category->id]) }}"
            >{{ ucwords($category->name) }}</a>
          @endforeach
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('categories.index') }}">
            All Categories
          </a>
        </div>
      </li>
    </ul>

    @search(['size' => 'md'])
      <!-- print search form -->
    @endsearch

  </div>
</nav>
<!-- End Navigation Bar -->
