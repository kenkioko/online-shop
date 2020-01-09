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

  @php
    use Illuminate\Support\Facades\Auth;
    use App\Order;
    use App\User;

    $user = Auth::user();
  @endphp

  @auth
    @php
    $new_orders = Order::has('user', $user->id)->count();
  @endphp
    <span class="navbar-text p-1 order-3">
      <a class="btn btn-outline-light ml-5" type="button"
        href="{{ route('orders.index') }}"
      >CART <span class="badge badge-danger m-1">{{ $new_orders }}</span></a>
    </span>
  @endauth

  <span class="navbar-text p-1 order-3">
    <ul class="navbar-nav mr-5">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-light"
          id="navbarDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          @auth
            {{ $user->name }}
          @endauth
          @guest
            Login
          @endguest
        </a><!-- /.nav-link -->
        <div class="dropdown-menu dropdown-menu-right " aria-labelledby="navbarDropdown">
          @auth
            @if ($user->user_level === 'admin')
              <span class="dropdown-item text-dark">
                <a type="button" class="btn btn-sm btn-secondary w-100 pop"
                  href="{{ route('admin.dash') }}"
                >Admin Dashboard</a>
              </span>
            @endif

            @logout(['display' => 'text'])
              <!--print logout button -->
            @endlogout
          @endauth
          @guest
            <span class="dropdown-item text-dark">
              <a type="button" class="btn btn-sm btn-secondary w-100 pop"
                href="{{ route('login') }}"
              >Login</a>
            </span>
            <span class="dropdown-item text-dark">
              <a type="button" class="btn btn-sm btn-secondary w-100 pop"
                href="{{ route('register') }}"
              >Register</a>
            </span>
          @endguest
        </div><!-- /.dropdown-menu -->
      </li><!-- /.nav-item -->
    </ul><!-- /.navbar-nav -->
  </span> <!-- /.navbar-text -->

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
