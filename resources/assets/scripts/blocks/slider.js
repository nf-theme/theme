(function($) {
  /**
   * initializeBlock
   *
   * Adds custom JavaScript to the block HTML.
   *
   * @date    15/4/19
   * @since   1.0.0
   *
   * @param   object $block The block jQuery element.
   * @param   object attributes The block attributes (only available when editing).
   * @return  void
   */
  var initializeBlock = function($block) {
    $block.find(".slides").slick({
      dots: true,
      arrows: true,
      infinite: true,
      autoplay: true,
      speed: 1500,
      slidesToShow: 1,
      adaptiveHeight: true
    });
  };

  // Initialize each block on page load (front end).
  $(document).ready(function() {
    var window_width = $(window).width();
    $(".slider").each(function() {
      initializeBlock($(this));
    });
    const sliders = document.querySelectorAll(".slider");
    Array.from(sliders).forEach(function(slider) {
      var id = $(slider).attr("id");
      var get_box_class = $("#" + id + " .image-slide")
        .attr("class")
        .split(" ");
      var height_class = get_box_class[1].split("-");
      if (window_width >= 1200) {
        $("#" + id + " .image-slide").css("height", height_class[0]);
      }
      if (window_width < 1200 && window_width > 992) {
        $("#" + id + " .image-slide").css("height", height_class[1]);
      }
      if (window_width < 992 && window_width > 768) {
        $("#" + id + " .image-slide").css("height", height_class[2]);
      }
      if (window_width < 768 && window_width < 480) {
        $("#" + id + " .image-slide").css("height", height_class[3]);
      }
      if (window_width < 480) {
        $("#" + id + " .image-slide").css("height", height_class[4]);
      }
    });
  });

  // Initialize dynamic block preview (editor).
  if (window.acf) {
    window.acf.addAction("render_block_preview/type=slider", initializeBlock);
  }
})(jQuery);
