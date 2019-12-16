<!-- SEARCH FORM -->
<form class="form-inline mx-3 w-100">
  @if($size == 'sm')
  <div class="input-group input-group-sm w-100">
  @else
  <div class="input-group input-group w-100">
  @endif
    <input
      class="form-control form-control-navbar"
      type="search"
      placeholder="Search"
      aria-label="Search"
    >
    <div class="input-group-append">
      <button class="btn btn-navbar btn-light" type="submit">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </div>
</form>
