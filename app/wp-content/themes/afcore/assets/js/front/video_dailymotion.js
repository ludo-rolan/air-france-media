jQuery(document).ready(function($) {
    $(".plyr-dailymotion").each(function() {
        let dm_iframe = $(this).find('.plyr-dailymotion-iframe');
        let dm_height = parseFloat(dm_iframe.height());
        let dm_width = parseFloat(dm_iframe.width());
        dm_iframe.data('aspectRatio', dm_width/dm_height);
        $(this).height(parseFloat($(this).width()) / dm_iframe.data('aspectRatio'))
        dm_iframe.height('100%');
        dm_iframe.attr('style', 'width: 100% !important');
    });

    $( window ).resize(function() {
        $(".plyr-dailymotion").each(function() {
            let dm_iframe = $(this).find('.plyr-dailymotion-iframe');
            $(this).height(parseFloat($(this).width()) / dm_iframe.data('aspectRatio'))
        });
    });
});

var set_iframe_height;