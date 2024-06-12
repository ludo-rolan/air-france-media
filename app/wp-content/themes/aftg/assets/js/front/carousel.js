jQuery(document).ready(function($) {
    $(".gallery_trait").each(function(index) {
        $(this).before("<span class='gallery_trait_curItem'></span");
        $(this).after("<span class='gallery_trait_totalItems'></span");
        $(this).attr('id', function() {
            return "trait" + index;
        });
    });
    $(".gallery_carousel").each(function(index) {
        $(this).attr('id', function() {
            return "carousel" + index;
        });
        $("#carousel" + index).owlCarousel({
            items: 1,
            margin: 80,
            dots: false,
            loop: true,
            onInitialized: avionCounter, // Quand le plugin est initialisé.
            onTranslated: avionCounter // Quand la translation est finie 
        });
        $('.owl-prev' + index).on('click', function() {
            $('#carousel' + index).trigger('prev.owl.carousel', [1200]);
            history.replaceState({}, null, "#item="+(index+1));
            dfp_refresh_all_ads();

        });
        $('.owl-next' + index).on('click', function() {
            $('#carousel' + index).trigger('next.owl.carousel', [1200]);
            history.replaceState({}, null, "#item="+(index+1));
            dfp_refresh_all_ads();
            
        });

        function avionCounter(event) {
            var totalItems = event.item.count; // Nombre des items dans la carousel
            var pos = (event.item.index + 1) - event.relatedTarget._clones.length / 2; // position de l'élément courant
            // re-set pos from 1
            if (pos > totalItems) {
                pos = 1;
            }else if(pos == 0){
                pos = totalItems - (pos % totalItems);
            }
            function DeuxDigits(nbr) {
                return (nbr < 10 ? '0' : '') + nbr;
            }
            $("#trait" + index).prev('.gallery_trait_curItem').text(DeuxDigits(pos));
            $("#trait" + index).next('.gallery_trait_totalItems').text(DeuxDigits(totalItems));
        }
    });
});