<!-- SEARCH FORM -->
<form class="form-inline px-3 w-100">
  <div class="input-group input-group{{ ($size == 'sm') ? '-sm': ''}} border border-light w-100">
    <div class="input-group-prepend bg-light">
      <button class="btn btn-navbar" type="submit">
        <i class="fas fa-search"></i>
      </button>
    </div>
    <input
      class="form-control form-control-navbar bg-light border-0"
      type="search"
      placeholder="Search for a product or service"
      aria-label="Search"
    >
    <div class="input-group-append bg-light">
      <button class="btn btn-navbar text-primary" type="submit">
        <i class="fas fa-map-marker-alt"></i>
      </button>
    </div>
  </div>
</form>
