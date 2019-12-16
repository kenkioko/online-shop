<form method="post"
  class="dropdown-item text-dark"
  action="{{ route('logout') }}"
> @csrf
  <button type="submit"
    class="btn btn-sm btn-secondary w-100 pop"
    data-container="body" data-toggle="popover" data-placement="bottom"
    data-content="logout"
  >
    @if($display === 'icon')
      <i class="fas fa-sign-out-alt"></i>
    @else
      Logout
    @endif
  </button>
</form>
