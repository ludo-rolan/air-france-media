jQuery(document).ready(function ($) {

    let podcasts_per_slide = $('body').hasClass('habillage-af') ? 1.7 : 2;

    let time = 250, // time in milliseconds
        isPause,
        tick,
        percentTime;

    let podcast_owl = $('.podcast-carousel');
    $(window).scroll(function () {
        let podcast_id = $('#hp-podcast-carousel');
        if(podcast_id.length){
        let divTop = podcast_id.offset().top,
            divOuterHeight = podcast_id.outerHeight(),
            windowHeight = $(window).height(),
            windowScroll = $(this).scrollTop();
        // if (windowScroll > (divTop + divOuterHeight - windowHeight) && (divTop > windowScroll) && (windowScroll + windowHeight > divTop + divOuterHeight)) {
            podcast_owl.owlCarousel({
                center: true,
                lazyLoad: true,
                autoplayTimeout: 6000,
                autoplaySpeed: 2000,
                slidesToShow: 1,
                responsiveClass: true,
                navSpeed: 1600,
                dragEndSpeed: 1600,
                responsive: {
                    0: {
                        items: 1,
                        nav: false,
                        dots: false,
                        stagePadding: 45,
                    },
                    768: {
                        items: 2,
                        nav: false,
                        dots: false,
                        stagePadding: 50,
                    },
                    1000: {
                        items: podcasts_per_slide,
                        nav: true,
                        onInitialized: function (elem) {
                            showProgressBar();
                            let elemCount = elem.item.count;
                            let currentElem = elem.relatedTarget.current() + 1;
                            $('.progress-container span:first-child').text(TowDigits(currentElem));
                            $('.progress-container span:last-child').text(TowDigits(elemCount));
                            startProgressBar();
                        },
                        onTranslated: progressBarMoved,
                        onDrag: pauseOnDragging,
                        onChanged: function (elem) {
                            let currentElem = elem.relatedTarget.current() + 1;
                            $('.progress-container span:first-child').text(TowDigits(currentElem));
                        },
                        onDragged: function (elem) {
                            let currentElem = elem.relatedTarget.current() + 1;
                            $('.progress-container span:first-child').text(TowDigits(currentElem));
                            showProgressBar();
                        },
                    }
                },
            });
         }
    });

    $('.owl-prev--podcast').on('click', function () {
        clearTimeout(tick);
        podcast_owl.trigger('prev.owl.carousel', [2000]);
        showProgressBar();
    });
    $('.owl-next--podcast').on('click', function () {
        clearTimeout(tick);
        podcast_owl.trigger('next.owl.carousel', [2000]);
        showProgressBar();
    });

    function showProgressBar() {
        let progress_bar_animation = $('#podcast-dynamic.progress-bar');
        let podcast_carousel_items = podcast_owl.find('div[class^="owl-item"]');

        podcast_carousel_items.each(function (e) {
            progress_bar_animation.css({width: "0%"}).attr('aria-valuenow', 0)
            $(this).find('.af__owl-nav').css('opacity', 0);
            $(this).find('.progress').css('opacity', 0);
            $(this).find('.progress-container span').css('opacity', 0);

            if ($(this).find('.progress-container div[class="progress-bar active-bar"]').length > 0) {
                $(this).find('.progress-container div[class="progress-bar active-bar"]').removeClass('active-bar');
            }

            if ($(this).hasClass('center')) {
                $(this).find('.af__owl-nav').css('opacity', 1);
                $(this).find('.progress').css('opacity', 1);
                $(this).find('.progress-container span').css('opacity', 1);
                progress_bar_animation.css({width: "100%"}).attr('aria-valuenow', 100)
                if (e !== podcast_carousel_items.length - 1) {
                    $(this).find('.progress-container div[class="progress-bar"]').addClass('active-bar');
                }
            }
        });
    }

    function TowDigits(nbr) {
        return (nbr < 10 ? '0' : '') + nbr;
    }

    function startProgressBar() {
        // reset timer
        percentTime = 0;
        isPause = false;

        tick = setInterval(interval, time);
    }

    function interval() {
        if (isPause === false) {
            percentTime += 1;

            $('#podcast-dynamic.active-bar')
                .css({width: percentTime + "%"})
                .attr("aria-valuenow", percentTime);

            // if percentTime is equal or greater than 100
            if (percentTime >= 100) {
                // slide to next item
                podcast_owl.trigger("next.owl.carousel", [2500]);
                showProgressBar()
                percentTime = 0; // give the carousel at least the animation time ;)
            }
        }
    }

// pause while dragging
    function pauseOnDragging() {
        isPause = true;
    }

// moved callback
    function progressBarMoved() {
        // clear interval
        clearTimeout(tick);
        // start again
        startProgressBar();
    }
});