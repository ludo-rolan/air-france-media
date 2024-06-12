jQuery(document).ready(function ($) {
    const class_container = ".rechercher-pop";
    const class_name = ".rechercher-pop-results span";
    const class_input = ".rechercher-input";
    let active_lang = $(".lang-switch").length ? '/' + $(".lang-switch").find(".lang_menu_item.active").first().text()+'/' : '/fr/';
    let search_url_lang = active_lang !== '/fr/' ? active_lang : '';

    var searchTypingTimer;
    $(class_input).keydown(function (e) {
        clearTimeout(searchTypingTimer);
    });
    $(class_input).keyup(function (e) {
            clearTimeout(searchTypingTimer);
            searchTypingTimer = setTimeout(SearchDoneTyping, 700, e);
        });

    function SearchDoneTyping (e) {
        if ($(class_input).val() && $(class_input).val().length > 2) {
            if (e.keyCode == 13) {
                $(class_input + '-btn').click();
            }
            else {
                $.ajax({
                    url: search_data.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'autocomplete_destinations',
                        nonce: search_data.nonce,
                        keyword: $(class_input).val()
                    },
                    success: function (cities) {
                        $(class_container).find('.rechercher-pop-results').remove();
                        $.ajax({
                            url: search_data.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'posts_count_search',
                                nonce: search_data.nonce,
                                cities: cities,
                                keyword : $(class_input).val()
                            },
                            success: function (counted_cities) {
                                $(class_container).css('display', 'block');
                                citiesArray = []
                                if(IsJsonString(counted_cities)) citiesArray = $.parseJSON(counted_cities);
                                citiesArray.forEach(city => {
                                    $(class_container).append(
                                        "<div class='rechercher-pop-results' data-url='"+document.location.origin+ search_url_lang +"?s="+city[0]+"'>" +
                                        "<span>" + city[0] + "</span>" +
                                        "<span>" + city[1]+ ' ' +city[2] + "</span>" +
                                        "</div>"
                                    );
                                });
    
                                $('.rechercher-pop-results').click(function () {
                                    window.location.href = $(this).attr("data-url");
                                });
                            }
        
                        });
                    }
                });
            }
        }
    }
    

    //cacher les résultats si on tape sur l'input 
    $(class_input).keypress(function () {
        $(class_container).css('display', 'none');
    });
    $(class_input).keydown(function () {
        $(class_container).css('display', 'none');
    });

    $('.close ').on('click', function () {
        $(class_container).css('display', 'none');
        $(class_input).val("");
    });

    $(class_input + '-btn').on('click', function () {
        window.location.href = document.location.origin+ search_url_lang +"?s="+ $(class_input).val();
    });

});

function IsJsonString(str) {
    try {
      var json = JSON.parse(str);
      return (typeof json === 'object' && json !== null);
    } catch (e) {
      return false;
    }
}