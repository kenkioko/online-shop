<ul class="navbar-nav">
  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle"
    id="navbarDropdown"
    role="button"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false"
  >Categories</a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    <!-- List all categories -->
    @php
    $categories = App\Models\Category::all();
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