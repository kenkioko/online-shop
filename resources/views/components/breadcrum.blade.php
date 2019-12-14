<!-- Breadcrums -->
<ol class="breadcrumb
@if(trim($extra_class) !== '')
  {{ $extra_class }}
@endif"
>{{ $slot }}</ol>
<!-- End Breadcrums -->
