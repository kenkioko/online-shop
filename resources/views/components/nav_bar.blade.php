<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top d-flex shadow">
  <a class="navbar-brand" href="{{ route('home.index') }}">
    <img src="{{ asset('/adminlte/dist/img/AdminLTELogo.png') }}" alt="Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: .8" height="40rem"
    >
    <span class="brand-text font-weight-bold m-3">My Shop</span>
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

  @can('cart.view')
    <span class="navbar-text p-1 order-3">
      <a class="btn btn-sm text-secondary" type="button"
        href="{{ route('cart.index') }}"
      >Cart <span class="badge badge-primary m-1">{{ Auth::user()->getCartItems() }}</span></a>
    </span>
  @endcan

  <div class="navbar-nav p-1 order-3">  
    @nav_profile()
    @endnav_profile    
  </div>

  <div class="navbar-nav p-1 order-3">  
    @auth 
      @logout(['display' => 'text'])
        <!--print logout button -->
      @endlogout
    @endauth
  </div>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <div class="mx-3">
      @nav_category()
      @endnav_category
    </div>

    <div class="form-inline w-100">
      @search(['size' => 'md'])
        <!-- print search form -->
      @endsearch
    </div>    

  </div>
</nav>
<!-- End Navigation Bar -->
