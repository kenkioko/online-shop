<script type="text/javascript">
  function format_number(float_number) {
    return float_number.toLocaleString(
      undefined, // leave undefined to use the browser's locale,
                 // or use a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    )
  }
</script>
