@include('shared.format_number_js')

<script type="text/javascript">
  function edit_type_update(original_val, price, input_id, display_id, update_type_id, discount=0) {
    var value = edit_type(original_val, price, input_id, display_id, discount);
    change_update_type(update_type_id, original_val, value);
  }

  function edit_type(original_val, price, input_id, display_id, discount=0) {
    var value = $('#'+input_id).val();
    var new_value = (price - discount) * value;

    $('#'+display_id).text(format_number(new_value));

    return value;
  }

  function change_update_type(update_type_id, original_val, value) {
    if (original_val == value) {
      $('#'+update_type_id).val('add');
    } else {
      $('#'+update_type_id).val('edit');
    }
  }
</script>
