jQuery(document).ready(function($) {

    // When nav menu list button clicked, remove 'open' class from the nav search collapse if it exists
    // then add/remove 'open' class to/from the menu list div.
    $('#navbarMobileContentBtn').on('click', function() {
        $("#navbarMobileSearchBtn").removeClass('open');
        $(this).toggleClass('open');
        if( $('#fog').length )
        {
            // it exists
            $('#fog').toggleClass('error404_fog_moved');
        }
    });

    // when a button with class 'nav-mobile-button' is clicked, hide all the collapses.
    $('.nav-mobile-button').click(function(e) {
        $('.nav-mobile-collapse').collapse('hide');

    });

    $('.wp-block-image').css('transform', 'translateX(-10%)');
});