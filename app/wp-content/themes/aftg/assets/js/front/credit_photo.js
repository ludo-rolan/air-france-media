jQuery(document).ready(function($) {

    $('.collapse-container.credits').hide(); 
    $('[data-alphabet="#"]').show(); 
    $('[data-alphabet="index"]').show();
    $(' .collapse-container [ data-alphabet="#"] a').css("color", "red");
    $(' .collapse-container [ data-alphabet="#"] .collapse-menu a').css("color", "red");

    $(' .collapse-container [ data-alphabet]').click(function () {
        alphabet=$(this).data( "alphabet" );
        $('.collapse-container.credits').hide(); 
        $('[data-alphabet="index"]').show();
        $('[data-alphabet="'+alphabet+'"]').show();
        $(' .collapse-container [ data-alphabet] a').css("color", "black");
        $(' .collapse-container [ data-alphabet="index"] a').css("color", "white");
        $(' .collapse-container [ data-alphabet="'+alphabet+'"] a').css("color", "red");

    });
    
});
