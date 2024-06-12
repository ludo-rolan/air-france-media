jQuery(document).ready(function ($) {
    if ($(window).width() < 1200) {
        // here MOBILE & TABLET
        return $('.parallax--bg').css('background-position', 'center');
    }
    // here LAPTOP AND DESKTOP
    $(window).scroll(function () {
        parallax();
    });
  

    function parallax() {
        let parallax_bg = $('.parallax--bg');
        let wScroll = $(window).scrollTop();

        parallax_bg.css('background-position', '0 -' + (wScroll * 0.85) + 'px');
    }
});