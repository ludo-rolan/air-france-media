jQuery(document).ready(function($) {
    $departing_from_input = $('input[name="departing-from"]');
    $departing_from_datalist = $('#departing-from');
    $arriving_at_input = $('input[name="arriving-at"]');
    $arriving_at_datalist = $('#arriving-at');
    var origins_key = 'embarquementOrigins';
    var all_origins_list = [];
    var all_origins_session = sessionStorage.getItem(origins_key);
    var all_destinations_list = [];

    $departing_from_input.focusout(function() { $departing_from_datalist.hide(); });
    $arriving_at_input.focusout(function() { $arriving_at_datalist.hide(); });
    $departing_from_input.focusin(function(e) {
        if($departing_from_datalist.children().length > 0) $departing_from_datalist.show();
        all_origins_session = sessionStorage.getItem(origins_key);
        if(!all_origins_session) {
            ajax_fetch_airports(e, '', false, origins_key);
        }
    });
    $arriving_at_input.focusin(function() { 
        if($arriving_at_datalist.children().length > 0) $arriving_at_datalist.show(); 
    });


    $('#flightBooking').submit(function (event) {
        //two possibilities => fr-FR or en-US
        lang = document.documentElement.lang == 'en-US' ? 'en' : 'fr';
        localisation = $("input[name='data-localisation']").val();
        var countryStr = localStorage.getItem("country");
        var country = JSON.parse(countryStr);
        let deeplink = "https://wwws.airfrance.fr/exchange/deeplink?language="+lang+"&country="+country.value+"&target=/search&";
        event.preventDefault();
        $selected_origin = $('#departing-from div[data-name="' + $departing_from_input.val() + '"]').attr('data-value');
        $selected_destination = $('#arriving-at div[data-name="' + $arriving_at_input.val() + '"]').attr('data-value');
        
        if(!$selected_origin) {
            $departing_from_input.val("").trigger('keyup');
            $departing_from_datalist.hide();
            $('.inspirations-input-warning-from').show();
        }
        else if(!$selected_destination) {
            $arriving_at_input.val("").trigger('keyup');
            $arriving_at_datalist.hide();
            $('.inspirations-input-warning-at').show();
        }
        else {
            let form_data = $(this).serializeArray().reduce(function(form_array, form_item) {
                form_array[form_item.name] = form_item.value;
                form_array['departing-from-iata'] = $selected_origin;
                form_array['arriving-at-iata'] = $selected_destination;
                return form_array;
            }, {});
            form_data['aller-date'] = form_data['aller-date'] != '' ? ':'+form_data['aller-date'].split('-').reverse().join('') : '';
            form_data['retour-date'] = form_data['retour-date'] != '' ? ':'+form_data['retour-date'].split('-').reverse().join('') : '';
            // the searching section based on two keywords => A or C ?
            // A means that is an airport & C means that is a city
            // type_arriving / type_departing returns A or C :)
            // after reading the DB, I released that C type doesnt contain ' - '
            var type_arriving = form_data['arriving-at'].includes(' - ')  ? 'A' : 'C'
            var type_departing = form_data['departing-from'].includes(' - ') ? 'A' : 'C'

            redirection_url = deeplink+"pax="+form_data['passengers']+":0:0:0:0:0:0:0&connections="
                +form_data['departing-from-iata']+":"+type_departing+form_data['aller-date']+">"
                +form_data['arriving-at-iata']+":"+type_arriving+"-"+form_data['arriving-at-iata']+":"+type_arriving+form_data['retour-date']+">"+form_data['departing-from-iata']+":"+type_departing
                + "&cabinClass="+form_data['cabin']+ '&' + utm_params.utm_params;
            window.open(redirection_url,'_blank');
        }
    });
    $( function() {
        var dateFormat = "dd-mm-yy",
            aller = $( "#datepicker-aller" )
                .datepicker({ dateFormat: dateFormat, minDate: 0 })
                .on( "change", function() {
                    retour.datepicker( "option", "minDate", getDate( this ) );
                }),
            retour = $( "#datepicker-retour" )
                .datepicker({ dateFormat: dateFormat })
                .on( "change", function() {
                    aller.datepicker( "option", "maxDate", getDate( this ) );
            });

            $("#datepicker-aller-calendar").click(function() {
                $("#datepicker-aller").datepicker("show");
            });
            $("#datepicker-retour-calendar").click(function() {
                $("#datepicker-retour").datepicker("show");
            });
     
        function getDate( element ) {
           return $.datepicker.parseDate( dateFormat, element.value );
        }
    });

    $('#trip-kind').on('change', function() {
        if($(this).val() == 'aller') {
            $("#datepicker-retour").hide().val('');
            $("#datepicker-retour-calendar").hide();
            $("#datepicker-aller").removeClass('inspirations-datepicker');
            $("#datepicker-aller").datepicker("option", "maxDate", null);
        }
        else if($(this).val() == 'aller-retour') {
            $("#datepicker-aller").addClass('inspirations-datepicker');
            $("#datepicker-retour").show();
            $("#datepicker-retour-calendar").show();
        }
    });

    ajax_fetch_airports = function(e, keyword, is_arriving, embarquement_key) {
        return new Promise(function(resolve, reject) {
        ajaxData = {
            action: 'autocomplete_airports',
            keyword: keyword
        }
        if(is_arriving) {
            ajaxData['origin_id'] = $selected.attr('data-id');
        }
        $.ajax({
            url: '/wp-json/afmm/embarquement',
            type: 'GET',
            data: ajaxData,
            success: function(data) {
                if(is_arriving) {
                    resolve(data);
                }
                else {
                    sessionStorage.setItem(embarquement_key, JSON.stringify(data));
                }
            }                
        });
    });
    }

    $arriving_at_input.keyup(function(e){
        destinationDoneTyping(e);
    });

    function destinationDoneTyping (e) {
        // second input
        $arriving_at_datalist.empty();
        $arriving_at_datalist.show();
        let found_destination = all_destinations_list.filter(element => element.full_name.toLowerCase().startsWith($arriving_at_input.val().toLowerCase()));
        found_destination.forEach(airport => {
            $arriving_at_datalist.append(
                "<div onmousedown='setDestinationValue(event, this)' class='inspirations-input-datalist-option'  data-name='"+airport['full_name']+"' data-id='"+airport['id']+"' data-value='"+airport['code_iata']+"'>"+airport['full_name']+"</div>"
            );
        });
    }

    $departing_from_input.keyup(function(e){
        originDoneTyping(e);
    });

    function originDoneTyping (e) {
        // first input
        $('.inspirations-input-warning-from').hide();
        $('.inspirations-input-warning-at').hide();
        $arriving_at_input.val("");
        $arriving_at_datalist.empty();
        all_origins_session = sessionStorage.getItem(origins_key);
        if(!all_origins_session) {
            ajax_fetch_airports(e, '', false, origins_key);
        }
        else if ($departing_from_input.val() && $departing_from_input.val().length > 1){
            $selected = $('#departing-from div[data-name="' + $departing_from_input.val() + '"]'); 
            if($selected.length) {
                ajax_fetch_airports(e, '', true, '').then(function(data) {
                    all_destinations_list = data;
                    all_destinations_list.sort( (a,b)=>( a.full_name>b.full_name ? 1:-1 ) );
                    all_destinations_list.forEach(airport => {
                        $arriving_at_datalist.append(
                            "<div onmousedown='setDestinationValue(event, this)' class='inspirations-input-datalist-option'  data-name='"+airport['full_name']+"' data-id='"+airport['id']+"' data-value='"+airport['code_iata']+"'>"+airport['full_name']+"</div>"
                        );
                    });
                });
            }
            else {
                all_origins_list = JSON.parse(all_origins_session);
                all_origins_list = all_origins_list.filter(element => element.full_name.toLowerCase().startsWith($departing_from_input.val().toLowerCase()));
                $departing_from_datalist.empty();
                all_origins_list.forEach(airport => {
                    $departing_from_datalist.append(
                        "<div onmousedown='setOriginValue(event, this)' class='inspirations-input-datalist-option'  data-name='"+airport['full_name']+"' data-id='"+airport['id']+"' data-value='"+airport['code_iata']+"'>"+airport['full_name']+"</div>"
                    );
                });
                $departing_from_datalist.show();
            }
        }
        else {
            $departing_from_datalist.empty();
        }
    }

    setOriginValue = function (event, element) {
        $departing_from_input.val($(element).text())
        originDoneTyping(event);
    }

    setDestinationValue = function (event, element) {
        $arriving_at_input.val($(element).text());
        destinationDoneTyping(event);
    }
});
var setOriginValue, setDestinationValue, ajax_fetch_airports;
