<a id="back-to-top" href="#" class="btn btn-primary back-to-top d-none" role="button" aria-label="Scroll to top">
  <i class="fas fa-chevron-up"></i>
</a>

<!-- Back to top button script -->
<script type="text/javascript">
  //Get the button
  var top_btn = document.getElementById("back-to-top");

  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      // top_btn.style.display = "block";
      top_btn.classList.remove("d-none");
    } else {
      // top_btn.style.display = "none";
      top_btn.classList.add("d-none");
    }
  }
</script>
