<ul class="navbar-nav p-1 order-3">
  <li class="nav-item">
    <a class="nav-link" aria-current="page" href="#">
      Products
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" aria-current="page" href="#">
      Services
    </a>
  </li>
  @guest
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="{{ route('login') }}">
        Login
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="{{ route('register') }}">
        Register
      </a>
    </li>
  @endguest

  @auth 
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="{{ route('login') }}">
        Profile
      </a>
    </li>

    @can('dashboard.view')
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="{{ route('admin.dash') }}">
        Dashboard
        </a>
      </li>
    @endcan       
  @endauth
</ul>
