jQuery(document).ready(function($) {
    $(document).keypress(function(e) {
        if(e.which == 13 && !$(".ui-autocomplete-input").is(':focus')) {
            let filters_content = $('#modal-body-search').children("div");
            filters_content.each(function(number){
                filter=filters_content[number];
                if(filter.className==""){
                    
                    $('.multi_select_filter-btn').click();
                }
            });
    }
});
    $('.dest-search-result').css({ 'background-color': '#838899'})
    let filters = $(".filters").children();
    filters.each(function (index) {
        let filters_children = $(this);

        filters_children.click(function (){
            let filters_content = $('#modal-body-search').children("div").eq(index);
            filters_content.removeClass('d-none');
            $('#destSearch').on('hidden.bs.modal', function () {
                filters_content.addClass('d-none');
            });

            filters_content.find('.multi_select_filter-btn').click(function (){
                let filter_options = $('#filter_content_'+index+' select[name^="select_"] option:selected');
                let filter_options_values = filter_options.map(function (){
                    return $(this).data('id');
                }).get()


                filters_children.attr('data-before', `${filter_options.length}`);
                filters_children.addClass('active-bubble');

                if (filter_options.length === 0) {
                    filters_children.removeAttr('data-before');
                    filters_children.removeClass('active-bubble');
                }

                $('#destSearch').modal('hide');

                let filters_values = $('.filters_values');
                filters_values.attr('data-filter-' + index, filter_options_values);
            })
        });
    });
    /*
    //code dyal tfrgui3 mayt7lch à voir avec ABOUDIA/MEHDI.
    $("select[name='select_region'] option").each(function (index, elem) {
        let filter = $('.filter_item_select_region.'+ $(this).val())
        filter.click(function(){
            let _this = $(this);
            _this.siblings('.multi_select_filter_item--selected').removeClass("multi_select_filter_item--selected");
            _this.siblings().children('div').removeClass("multi_select_filter_item_img--selected");
            $(elem).siblings().removeAttr('selected')
            _this.addClass("multi_select_filter_item--selected");
            _this.children('div').addClass("multi_select_filter_item_img--selected");
            $(elem).attr('selected','true')
        })
    })*/

    $('.filter_select_region').children().click(function() {
        let child_div = $(this)[0];
        let destination_name = $(child_div).find('p').html()
        $('.multi_select_filter_result').children('span').html(destination_name)
    });

    let multi_select_filter_item_request;
    $(".filter_item_select_region[data-clickable=1]").on('click', function () {
        try { multi_select_filter_item_request.abort() } catch (e) {}
        let multi_select_filter_result_content = $('.multi_select_filter_result_content');
        $('.multi_select_filter_result h2').addClass('d-none')
        $('.multi_select_filter_result span').addClass('d-none')
        multi_select_filter_result_content.addClass('d-none');
        let elem = $(this);
        let option_id = elem.data('id');
        let is_content_clickable = elem.data('clickable')
        // on cherche avant si on a la data en browser sans requeter la BD
        // si on a pas de data on fait le requetage + on stocke la data en browser .
        // si on a de data en browser on fait que l'affichage de cette data 
        // utilisant la fonction parse_html
        if (localStorage.getItem(option_id) == null) {
            let lang = document.documentElement.lang.toLowerCase();
            let lang_code = lang.split('-')[0];
            multi_select_filter_item_request = $.ajax({
                url: '/wp-json/aftg/regions/' + lang_code,
                type: 'POST',
                data: {
                    action: 'regions',
                    region_id: option_id,
                    is_content_clickable: is_content_clickable
                },
                success: function (data) {
                    localStorage.setItem(option_id, JSON.stringify(data));
                    parse_html(data,multi_select_filter_result_content);
                }
            });
        } else {
            var data = JSON.parse(localStorage.getItem(option_id));
            parse_html(data,multi_select_filter_result_content);
        }
    })
    $('.multi_select_filter-btn').click(function () {
        $('.dest-search-loader').css('display', 'block');
        mutliple_search_destination();
    });
    $('.dest-search-result').click(function () {
        $('.dest-search-loader').css('display', 'block');
        mutliple_search_destination();
        if($("#result-button-tg").attr('data-ids')){
            $(function() {
                $('<form action="destination/" method="post">'
                    +'<input type="hidden" name="destinations_ids" value="'+$("#result-button-tg").attr('data-ids')+'"></input></form>').
                    appendTo('body').submit().remove();
            });

        }

    });
    
    // parse_html : une fonction qui se charge seulement à afficher les pays et ses villes
    // pourquoi une fonction psk on a besoin d'afficher les pays et les villes selon 
    // la condition de data en browser ou non cvd ignorer de requeter la BD à chaque clique
    // format de data [{'country_name' : '', 'country_slug':'','cities':{'city_name':'','city_slug':'','price':'','af_url':''}}]
    function parse_html(data, multi_select_filter_result_content) {
        $('#country').empty();
        $('.multi_select_filter_result h2').removeClass('d-none')
        $('.multi_select_filter_result span').removeClass('d-none')
        multi_select_filter_result_content.removeClass('d-none');
        $('.multi_select_filter_result').append("<div class='row col-md-12 mt-3 multi_select_filter_result_content' id='country'></div>");
        let i = 0;
        data.forEach(country_data => {
            if (country_data['country_name'].toString() != 'France'){
                $('#country').append('<div class="d-flex flex-column col-md-12 mt-3 country_content' + i + '"></div>');
                $('.country_content' + i).append('<a href="' + country_data['country_slug'] + '"><span>' + country_data['country_name'] + '</span></a>')
                country_data['cities'].forEach(city_data => {
                    $('.country_content' + i).append('<a href="' + city_data['city_slug'].replace('destinations','destination') + '">' + city_data['city_name'] + '</a>');
                    if(city_data['price']==null || city_data['price']==''){
                        $('.country_content' + i).append('<p></p>')
                    }else{
                        $('.country_content' + i).append('<p><a href="'+city_data['af_url']+'">'+city_data['vol_text']+ city_data['price'] + ' &euro; A/R*</a></p>')
                    }
                })
                i++;
            }else {
                // here we manage France zone ;
                var itemsforeachdiv = country_data['cities'].length/3;
                var counter = 0;
                for(var j=0; j<3; j++){
                    $('#country').append('<div class="d-flex flex-column col-md-12 mt-3 country_content_france' + j + '"></div>');
                    if (j == 0) {
                        $('.country_content_france' + j).append('<a href="' + country_data['country_slug'] + '"><span>' + country_data['country_name'] + '</span></a>');
                    }
                    while (country_data['cities'].length > 0){
                        if (counter == itemsforeachdiv){counter = 0 ; break;}
                        var city_data = country_data['cities'].shift();
                        $('.country_content_france' + j).append('<a href="' + city_data['city_slug'].replace('destinations','destination') + '">' + city_data['city_name'] + '</a>');
                        if(city_data['price']==null || city_data['price']==''){
                            $('.country_content_france' + j).append('<p></p>')
                        }else{
                            $('.country_content_france' + j).append('<p><a href="'+city_data['af_url']+'">'+city_data['vol_text']+ city_data['price'] + ' &euro; A/R*</a></p>')
                        }
                        counter ++;
                    }
                }
            }
        });
    }

    function mutliple_search_destination(){
        let desire_values = $.map($('select[name^="select_desire"] option:selected'), function (option) {
            return $(option).data('id');
        });
        let region_values = $.map($('select[name^="select_region"] option:selected'), function (option) {
            return $(option).data('id');
        });
        let departure_values = $.map($('select[name^="select_departure"] option:selected'), function (option) {
            return $(option).data('id');
        });
        let budget_args = {"passengers": $('#passenger_num').val(), "price-range": $('#price_num').val()}

        let filters_values = {
            "desires": desire_values,
            "region": region_values,
            "departure": departure_values,
            "budget": budget_args,
        }
        $.ajax({
            url: '/wp-json/aftg/get_filters_result/',
            type: 'POST',
            data: {
                action: 'filters_result',
                filters_ids: JSON.stringify(filters_values)
            },
            success: function (result) {
                if(result.ids.length){
                    $("#result-button-tg").text(result.msg +result.ids.length+" DESTINATIONS");
                    $('#result-button-tg').css({'cursor': 'pointer', 'pointer-events': 'auto', 'background-color': '#FF0000'})
                    $("#result-button-tg").attr('data-ids', result.ids);
                    aftg_tag =  JSON.stringify(result.tags);
                    $('.dest-search-loader').css('display', 'none');
                }
                else {
                    $("#result-button-tg").text(result.msg);
                    $('#result-button-tg').css({'cursor': 'pointer', 'pointer-events': 'auto', 'background-color': '#838899'})
                    $("#result-button-tg").attr('data-ids', result.ids);
                }
            }
        });


    }
});