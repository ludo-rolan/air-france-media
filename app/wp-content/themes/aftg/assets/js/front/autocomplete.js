jQuery(document).ready(function ($) {

    const path_groups = location.pathname.split('/').filter((v) => v.length > 0);
    const language = (path_groups[0] && path_groups[0].length == 2) ? path_groups[0] : 'fr';
    const lang_url =(path_groups[0] && path_groups[0].length == 2) ? "/"+path_groups[0] : '';
    let result = [];
    let result_suggestion = [];

    let key = language + 'coutriesData';
    let data = sessionStorage.getItem(key);
    if (data) {
        result_suggestion = JSON.parse(data).map(e => e['label']);
        $.map(JSON.parse(data), function (value) {
            result[value['label']] = value;

        })
        return autocomplete(result_suggestion, result);

    }
    $.ajax({
        url: lang_url+'/wp-json/aftg/get_autocomplete_items/',
        type: 'GET',
        cache: true,
        success: function (response) {
            sessionStorage.setItem(key, JSON.stringify(response));
            result_suggestion = response.map(e => e['label']);
            $.map(response, function (value) {
                result[value['label']] = value;
            })
            autocomplete(result_suggestion, result);
        }
    });

    function autocomplete(result_suggestion, result) {
        $(".ui-autocomplete-input").autocomplete({
            minChars: 1,
            source: function (request, response) {
                        var matches = $.map(result_suggestion, function (item) {
                            destination=(item.toLowerCase()).normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                            var search=request.term.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                            if (destination.indexOf(search) >= 0) {
                                return item;
                            }
                        });
                        $(".ui-autocomplete-input").keypress(function (e) {
                            var key = e.which;
                            if(key == 13){
                                $('.dest-search-loader').css('display', 'block');
                                window.location.href = result[matches[0]]['link'];
                            }
                        });
                    response(matches);
                    },
            appendTo: ".dest-search-suggestions_ul_container",
            select: function (event, ui) {
                $('.dest-search-loader').css('display', 'block');
                window.location.href = result[ui.item.label]['link'];
            },
            focus: function (event) {
                event.preventDefault();
            },
        });
        return 1;
    }
});