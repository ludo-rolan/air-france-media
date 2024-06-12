jQuery(document).ready(function($) {

    $(window).scroll(function () {
        let wScroll = $(window).scrollTop();
        let sticky = $('.sticky');
        if (wScroll > 60 && wScroll < 80) {
            sticky.css('top', '-30px');
            $('.crumbs-nospace').css('margin-top', '0')
        }
        if (wScroll > 80) {
            sticky.css('top', '0px');
            sticky.css('transition-duration', '0.9s');
            sticky.css('position', 'fixed');

        }else {
            sticky.css('position', 'relative');
        }
    });
});