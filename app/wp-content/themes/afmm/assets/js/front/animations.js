jQuery(document).ready(function($) {



    // START OF HOMEPAGE SVG ANIMATION
    const svg = $('#svg-path').get(0);
    let length=0;
    if (svg) {
        length = svg.getTotalLength();
    }
    function drawSVG() {
        $('.st0').css('display', 'block');
        // start positioning of svg drawing
        svg.style.strokeDasharray = length + 800;
        // hide svg before scrolling starts
        svg.style.strokeDashoffset = length + 800;
        const scroll_percent = (document.body.scrollTop + document.documentElement.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight);
        const draw = length * scroll_percent + ( window.innerHeight / 2 );
        // Draw svg and reverse the drawing when scroll upwards
        svg.style.strokeDashoffset = (length - draw) + 150;
    }
    // END OF HOMEPAGE SVG ANIMATION

    $(window).scroll(function () {
        let wScroll = $(window).scrollTop();
        let sticky = $('.sticky');
        if (wScroll > 100) {
            sticky.css('top', 0);
            sticky.css('transition-duration', '0.75s');
        }
        else {
            sticky.css('top', -110);
        }
        if (svg) {
            drawSVG();
        }
        check_if_in_view($('.post'), 'fadeClass');
        check_if_in_view($('.post-envol'), 'fadeClass');
        check_if_in_view($('.hp_alune_link'), 'slide-in');
        check_if_in_view($('.preview'), 'slide-in');
        check_if_in_view($('.hp_alune_title'), '', function (){
            $('.hp_alune_image').addClass('hp_alune_image_scaling');
        });
    });

});