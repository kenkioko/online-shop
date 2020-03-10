@if($item->discount_amount || $item->discount_percent)
  Was: <span style="text-decoration:line-through">
    {{ number_format($item->price, 2) }}
  </span>

  &nbsp; &sol; &nbsp; Now: <span>
    {{ number_format(($item->price - $item->discount_amount), 2) }}
  </span>

  &nbsp; <span class="badge badge-primary py-1">
   &minus; {{ $item->discount_percent }} &nbsp; &percnt;
 </span> <br>
@else
  {{ number_format($item->price, 2) }} <br>
@endif
