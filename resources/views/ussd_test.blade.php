@extends('layouts.app')

@section('title', 'USSD TEST')

@section('content')

<form class="m-5" action="{{ route('africastkng.index') }}" method="post">
  <input type="text" name="sessionId" placeholder="sessionId" value="">
  <input type="text" name="phoneNumber" placeholder="phoneNumber"  value="">
  <input type="text" name="networkCode" placeholder="networkCode" value="">
  <input type="text" name="serviceCode" placeholder="serviceCode" value="">
  <input type="text" name="text" placeholder="text" value="">

  <button type="submit" name="button">submit</button>
</form>

@endsection
