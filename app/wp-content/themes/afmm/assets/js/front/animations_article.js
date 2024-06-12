jQuery(document).ready(function ($) {
    $(document).on("scroll", function () {
        let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
        if (!isMobile) {
            check_if_in_view($('.sidebar_plusLus'), 'fadeClass');
            check_if_in_view($('.post-excerpt'), 'fadeClass');
            check_if_in_view($('.wp-block-column p'), 'fadeClass');
            check_if_in_view($('.via-fretta-block'), 'fadeClass');
            check_if_in_view($('.wp-block-quote'), 'fadeClass');
            check_if_in_view($('.wp-block-image'), 'slide-in');

            let vignettes = $('.visitez_endroit_visuel_vignette');
            vignettes.each(function(index, value) {
                check_if_in_view($(this), `fadeClass-${index}`);
            });

            let btn_partager = $('.btn-partager-mini a');
            btn_partager.each(function (index) {
                check_if_in_view($(this), `fadeClass_btn_partager-${index + 1}`);
            })
        }
    });
});