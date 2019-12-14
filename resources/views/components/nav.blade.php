<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top d-flex">
  <a class="navbar-brand" href="{{ route('home.index') }}">
    <img
      src="{{ route('home.index') }}/img/placeholder.com-logo1.jpg"
      alt="logo"
      width="100"
      height="50"
    />
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
    <button class="btn btn-outline-light my-2 my-sm-0" type="button">CART</button>
  </span>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-light"
          id="navbarDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <!-- List all categories -->
          @php
            $categories = App\Category::all();
          @endphp

          @foreach ($categories as $category)
            <a
              class="dropdown-item"
              href="{{ route('category.show', ['category' => $category->id]) }}"
            >{{ ucwords($category->name) }}</a>
          @endforeach
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('category.index') }}">
            All Categories
          </a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<!-- End Navigation Bar -->
