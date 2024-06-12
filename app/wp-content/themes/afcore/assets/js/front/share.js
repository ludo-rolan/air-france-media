jQuery(document).ready(function ($) {
    
    $(".btn-partager-mini").find('.btn-partager-click').click(function () {
        $(".btn-partager-mini").find('.btn-partager-autres-plus').toggleClass('btn-partager-autres-show btn-partager-autres-hidden'); 
    });
    $(".btn-partager").find('.btn-partager-click').click(function () {
        $(".btn-partager").find('.btn-partager-autres-plus').toggleClass('btn-partager-autres-show btn-partager-autres-hidden'); 
    });

    $(".copy-btn").on('click', function(){
        textToCopy = $(this).attr('data-copy');
        navigator.clipboard.writeText(textToCopy)
        
    });
});
