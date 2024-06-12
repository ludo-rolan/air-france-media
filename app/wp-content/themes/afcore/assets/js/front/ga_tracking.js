jQuery(document).ready(function ($) {

    let elements = [
        '#rdh_guide_header',
        '#rdh_guide_pastille',
        '#rdh_guide_title',
        '#rdh_guide_search',
        '#rdh_guide_stickymenu',
        '#bkt_guide_sidebar',
        '#rdh_media_maillage',
        '#bkt_media_sidebar',
        '#rdh_guide_vignette',
        '.lht_MEDIA',
        '.lht_GUIDE',
    ];

    $elements_to_jquery = elements.join(',');

    $($elements_to_jquery).click(function () {
        send_GA('af_redirection', 'clicked', $(this).attr('id'), 1);
    });
});