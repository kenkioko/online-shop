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
    console.log('add to cart')
  });

});
