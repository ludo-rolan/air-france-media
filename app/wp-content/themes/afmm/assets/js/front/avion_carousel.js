jQuery(document).ready(function($) {

    $(".avion_trait").each(function(index) {
        $(this).before("<span class='avion_trait_curItem'></span");
        $(this).after("<span class='avion_trait_totalItems'></span");
        $(this).attr('id', function() {
            return "trait" + index;
        });
    });
    $(".avion_carousel").each(function(index) {
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
        });
        $('.owl-next' + index).on('click', function() {
            $('#carousel' + index).trigger('next.owl.carousel', [1200]);
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
            $("#trait" + index).prev('.avion_trait_curItem').text(DeuxDigits(pos));
            $("#trait" + index).next('.avion_trait_totalItems').text(DeuxDigits(totalItems));
        }
        //Affichage de la modal Cabine image
        $('.avion_btn-cabine').on('click', function() {
            $(this).next('.modal').modal('show');
        });
    });

});