<div class="row">
  <div class="form-group col-sm-4">
    <label for="address_country_input">Country:</label>
    <input type="text" class="form-control" placeholder="country" name="address_country" id="address_country_input"
      value="@if (old('address_country')) {{ old('address_country') }} @elseif ($address->country) {{ $address->country }} @endif"
    >
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_state_input">State:</label>
    <input type="text" class="form-control" placeholder="state" name="address_state" id="address_state_input"
      value="@if (old('address_state')) {{ old('address_state') }} @elseif ($address->state) {{ $address->state }} @endif"
    >
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_city_input">City:</label>
    <input type="text" class="form-control" placeholder="city" name="address_city" id="address_city_input"
      value="@if (old('address_city')) {{ old('address_city') }} @elseif ($address->city) {{ $address->city }} @endif"
    >
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_street_input">Street:</label>
    <input type="text" class="form-control" placeholder="street" name="address_street" id="address_street_input"
      value="@if (old('address_street')) {{ old('address_street') }} @elseif ($address->street) {{ $address->street }} @endif"
    >
  </div><!-- /.form-group -->
  <div class="form-group col-sm-4">
    <label for="address_postcode_input">Postcode:</label>
    <input type="text" class="form-control" placeholder="postcode" name="address_postcode" id="address_postcode_input"
      value="@if (old('address_postcode')) {{ old('address_postcode') }} @elseif ($address->postcode) {{ $address->postcode }} @endif"
    >
  </div><!-- /.form-group -->
</div><!-- /.row -->
