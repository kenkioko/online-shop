<form method="post" class="text-dark" action="{{ route('logout') }}"> 
  @csrf
  
  <button type="submit"
    class="btn btn-sm btn-navbar btn-outline-primary"
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
