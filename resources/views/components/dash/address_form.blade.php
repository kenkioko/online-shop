@php
  $prefix = ($shop_address == true) ? "shop_" : "";

  $country = old("${prefix}address_country") ?? $address->country;
  $state = old("${prefix}address_state") ?? $address->state;
  $city = old("${prefix}address_city") ?? $address->city;
  $street = old("${prefix}address_street") ?? $address->street;
  $postcode = old("${prefix}address_postcode") ?? $address->postcode;
  $full_address = old("${prefix}full_address") ?? $address->full_address;
@endphp

<div class="row">
  <div class="form-group col-sm-4">
    <label for="address_country_input">Country:</label>
    <input type="text" class="form-control" placeholder="country" name="{{ $prefix }}address_country" id="address_country_input" value="{{ $country }}">
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_state_input">State:</label>
    <input type="text" class="form-control" placeholder="state" name="{{ $prefix }}address_state" id="address_state_input" value="{{ $state }}">
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_city_input">City:</label>
    <input type="text" class="form-control" placeholder="city" name="{{ $prefix }}address_city" id="address_city_input" value="{{ $city }}">
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_street_input">Street:</label>
    <input type="text" class="form-control" placeholder="street" name="{{ $prefix }}address_street" id="address_street_input" value="{{ $street }}">
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_postcode_input">Postcode:</label>
    <input type="text" class="form-control" placeholder="postcode" name="{{ $prefix }}address_postcode" id="address_postcode_input" value="{{ $postcode }}">
  </div><!-- /.form-group -->
</div><!-- /.row -->

<div class="row">
  <div class="form-group col-sm-12">
    <label for="address_postcode_input">Full Address:</label>
    <textarea class="form-control" name="{{ $prefix }}full_address" placeholder="full physical address">{{ $full_address }}</textarea>
  </div><!-- /.form-group -->
</div><!-- /.row -->