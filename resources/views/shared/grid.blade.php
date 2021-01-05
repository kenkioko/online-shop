<!-- Flickity HTML init -->
<div class="flickity-container my-5" 
  data-flickity='{ 
    "wrapAround": true,
    "freeScroll": true, 
    "autoPlay": 3000, 
    "imagesLoaded": true, 
    "draggable": true,
    "groupCells": true,
    "pageDots": false
  }'>

  @foreach([1,2,3,4,5,6,7,8] as $arr)
  <div class="flickity-cell">
    <img src="https://via.placeholder.com/300x150"/>
    <span class="flickity-inner badge bg-warning text-dark p-2">PROMOTED</span>
  </div>
  @endforeach

  <!-- click to book space -->
  <div class="flickity-cell border border-5 border-secondary border-dashed rounded">
    <div class="flex-column">
      <p class="h3 p-1 text-center">Book this space for your product</p>
      <p class="p-1 text-center">
        <a href="#">
          <i class="fas fa-plus-circle fa-3x text-primary p-1"></i>
        </a>
      </p>      
    </div>
  </div>
</div>