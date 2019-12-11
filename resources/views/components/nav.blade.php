<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top d-flex">
  <a class="navbar-brand" href="index.html">
    <img src="img/placeholder.com-logo1.jpg" alt="logo" width="100" height="50" />
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
    <button class="btn btn-outline-secondary my-2 my-sm-0" type="button">CART</button>
  </span>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      @if ($page === 'home')
        <li class="nav-item active">
          <a class="nav-link" href="#"> Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
      @else
        <li class="nav-item">
          <a class="nav-link" href="/">Home</a>
        </li>
      @endif

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"
          id="navbarDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/category">All Categories</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<!-- End Navigation Bar -->
