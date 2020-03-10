<!-- Cart Side Menu -->
<div class="col-sm-3">
  <div class="card sub-menu">
    <ul class="list-group list-group-flush">
      <li class="list-group-item bg-secondary">
        <h4 class="font-weight-bold">{{ $title }}</h4>
      </li>

      @foreach($menu_items as $item)
        <a href="{{ $item['url'] }}"
          class="list-group-item list-group-item-action @if(isset($item['active']) and $item['active']) active @endif"
        >{{ $item['name'] }}</a>
      @endforeach
    </ul>
  </div>
</div>
<!-- End Cart Side Menu -->
