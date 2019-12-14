@extends('layouts.dash')

@section('page_header', 'Dashboard')

@section('sidebar')
  @dash_sidebar(['page' => 'dashboard'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
  @endbreadcrum()
@endsection
