<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.dash') }}" class="brand-link">
    <img src="{{ asset('/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8"
    >
    <span class="brand-text font-weight-light">My Shop</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        @php
          use Illuminate\Support\Facades\Auth;
          $user_name = Auth::user()->name;
        @endphp

        <a href="#" class="d-block">Welcome {{ $user_name }}!</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}"
            class="nav-link @if ($page === 'users') active @endif"
          >
            <i class="nav-icon fas fa-users"></i>
            <p>Registered Users</p>
          </a>
        </li><!-- /.nav-item -->
        <li class="nav-item">
          <a href="{{ route('admin.orders.index') }}"
            class="nav-link @if ($page === 'orders') active @endif"
          >
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p> Orders

              @php
                use App\Order;
                $new_orders = Order::where('status', 'order_made')->count();
              @endphp

              @if($new_orders)
                <span class="right badge badge-danger">
                  {{ $new_orders }} New
                </span>
              @endif
            </p>
          </a>
        </li><!-- /.nav-item -->

        @if ($page === 'categories' or $page === 'items')
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
        @else
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
        @endif
            <i class="nav-icon fas fa-plus-circle"></i>
            <p>
              Add Categories/Items
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.categories.index') }}"
                class="nav-link @if ($page === 'categories') active @endif"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Categories</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.items.index') }}"
                class="nav-link @if ($page === 'items') active @endif"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Items</p>
              </a>
            </li>
          </ul>
        </li><!-- /.nav-item -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
