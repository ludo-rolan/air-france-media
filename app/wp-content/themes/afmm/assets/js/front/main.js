jQuery(document).ready(function($) {
    $('.footer_1 a').each(function() {
        $(this).text('');
    });
    $('#submit_nl').click(function(){
        email_nl = $('#email_nl').val();
        location.href = window.location.href+"/inscription-newsletter/?email_newsletter="+ email_nl;
    });
    $('#checkbox_nl').change(function(){
        email_nl = $('#email_nl').val();
        location.href = window.location.href+"/inscription-newsletter/?email_newsletter="+ email_nl;
    });
    // When nav search button clicked, remove 'open' class from the nav menu list collapse if it exists,
    // then add/remove 'open' class to/from the search div.
    $('#navbarMobileSearchBtn').on('click', function() {
        $("#navbarMobileContentBtn").removeClass('open');
        $(this).toggleClass('open');
        $(".rechercher-pop").css('display','none');
        $(".rechercher-input").val("");
    });

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