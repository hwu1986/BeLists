(function($){
$(document).ready(function(){

/* -------------------------------------------------------------------
  Fix clear for browser that do not support nth-child selector
------------------------------------------------------------------- */
function fixNth(){
  $('.post-block:first, .post-block:nth-child(3n+4)').addClass('first-item');
  $('.footer-widgets .widget:nth-child(4n+5)').addClass('first-item');
  $('.tab-panel .post-block:nth-child(odd)').addClass('odd');
  $('.tab-panel .post-block:nth-child(even)').addClass('even');
}
fixNth();

/* -------------------------------------------------------------------
  DropKick Initialization
------------------------------------------------------------------- */
$('.custom-select').each(function(i){
  $(this).attr('tabindex', i+1);
}).dropkick({
  change: function(value, label) {
    $(this).trigger('change');
  }
});


/* -------------------------------------------------------------------
  Main menu submenu
------------------------------------------------------------------- */
$('.main-nav ul:first')
  .addClass('sf-menu')
  .superfish({
    delay:        100,
    animation:    { opacity: 'show', height: 'show' },
    speed:        'fast',
    dropShadows:  false
  });


/* -------------------------------------------------------------------
  Mobile Menu
------------------------------------------------------------------- */
$('.main-nav .sf-menu').mobileMenu();


/* -------------------------------------------------------------------
  Category Listing Submenu
------------------------------------------------------------------- */
$('.category-listing ul:first')
  .addClass('sf-menu sf-vertical')
  .superfish({
    delay:        100,
    animation:    { opacity: 'show', height: 'show' },
    speed:        'fast',
    dropShadows:  false
  })
  .find('.sf-sub-indicator').html('&rsaquo;');


/* -------------------------------------------------------------------
  Featured Listing Slider
------------------------------------------------------------------- */
function featuredListing() {

  // Only run slider when all images loaded
  $('.featured-listing .slides').imagesLoaded(function(){
    var $el = $(this);

    $('<nav>').appendTo($el.parent());

    $el.carouFredSel({
      // auto: false,
      responsive: true,
      pagination: {
        container: $el.parent().find('nav'),
        anchorBuilder: function( nr, item ) {
          return '<a href="#'+nr+'">'+nr+'</a>';
        }
      }
    });

  });
}
featuredListing();


/* -------------------------------------------------------------------
  Items Carousel
------------------------------------------------------------------- */
function itemsCarousel(){
  $('.carousel').each(function(){
    var $el = $(this),
        navHtml = '<nav class="carousel-nav">\
                    <a href="#" class="prev"><span>&lsaquo;</span></a>\
                    <a href="#" class="next"><span>&rsaquo;</span></a>\
                  </nav>';

    // Create Navigation
    $(navHtml).insertBefore($el);

    // Remove margin on 5th carousel item
    // console.log( $el.find('.carousel-item').eq('3').addClass('fourth') );
    

    $el.imagesLoaded(function(){
      $el.carouFredSel({
        // auto: false,
        circular: false,
        infinite: false,
        responsive: true,
        width: '100%',
        prev: $el.parent().find('.prev'),
        next: $el.parent().find('.next'),
        items: {
          width: 320,
          minimum: 2,
          visible: {
            min:    1,
            max:    4
          }
        }
      });
    });

  });
}
itemsCarousel();


/* -------------------------------------------------------------------
  Content Tab
------------------------------------------------------------------- */
function contentTab() {
  $('.content-tab').each(function(){
    var $el = $(this);

    // Add class 'selected' to first tab navigation
    $('.tab-nav li', $el).first().addClass('selected');

    // Hide other tab panel except the first one
    $('.tab-panel', $el).not(':first').hide();

    // Click event for tab navigation
    $('.tab-nav a', $el).click(function(e){
      e.preventDefault();
      var $this = $(this),
          target = $this.attr('href');

      // Change selected class
      $this.parent().siblings().removeClass('selected').end().addClass('selected');

      // Show target panel
      $('.tab-panel', $el).fadeOut(200);
      $(target).delay(200).fadeIn(200);

    });

  });
}
contentTab();


/* -------------------------------------------------------------------
  Placeholder polyfill
------------------------------------------------------------------- */
$('#commentform').each(function(){
  var $el = $(this);

  $el.find('input, textarea')
    // On Focus
    .focus(function(){
      var $that = $(this);
      if( $that.val() === '' ) {
        $that.prev().fadeOut(200);
      }
    })
    // On blur
    .blur(function(){
      var $that = $(this);
      if( $that.val() === '' ) {
        $that.prev().fadeIn(200);
      }
    });
});

});
})(jQuery);