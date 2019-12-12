$(function(){

  /*
   * Flickity instance
   */
  $('.grid-container').flickity({
    wrapAround: true,
    pageDots: false,
    cellAlign: 'left',
    contain: true
  });

  $('.add-cart').click(function () {
    add_to_cart();
  });

});

function add_to_cart() {
  console.log('add to cart');
}
