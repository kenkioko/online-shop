<form method="post"
  class="dropdown-item text-dark"
  action="{{ route('logout') }}"
> @csrf
  <button class="btn btn-sm btn-secondary w-100" type="submit">
    @if($display === 'icon')
      <i class="fas fa-sign-out-alt"></i>
    @else
      Logout
    @endif
  </button>
</form>
