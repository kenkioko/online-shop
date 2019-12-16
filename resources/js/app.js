$(function () {
  // Toogle popovers
  $('.pop').hover(function () {
    $(this).popover('show');
  }, function () {
    $(this).popover('hide');
  });
})
