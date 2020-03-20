<script type="text/javascript">
  function edit_type_withid(original_val, price, input_id, display_id, update_type_id, discount=0) {
    var value = edit_type(original_val, price, input_id, display_id, discount);
    change_update_type(update_type_id, original_val, value);
  }

  function edit_type(original_val, price, input_id, display_id, discount=0) {
    var value = $('#'+input_id).val();
    var new_value = (price - discount) * value;

    $('#'+display_id).text(new_value.toLocaleString(
      undefined, // leave undefined to use the browser's locale,
                 // or use a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    ));

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
