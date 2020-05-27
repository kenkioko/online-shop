@extends('layouts.app')

@section('title', 'Shop Details')

@section('content')

  @breadcrum(['extra_class' => 'w-100 p-2 text-muted'])
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Shop details</li>
  @endbreadcrum()

<div class="container-fluid">
  {{ $shop }}
</div>

@endsection
