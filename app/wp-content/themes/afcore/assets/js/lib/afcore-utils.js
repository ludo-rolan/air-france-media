jQuery(document).ready(function ($) {
    var requestUrl = "https://freeipapi.com/api/json";
        var countryStr = localStorage.getItem("country");
        var country = isJSON(countryStr)? JSON.parse(countryStr):"";
        var now = new Date();
        if (country=="" || now.getTime() > country.expiry){
            $.ajax({
                url: requestUrl,
                type: 'GET',
                success: function(json)
                {
                    var now = new Date();
                    country = {
                        value: json.countryCode.toLowerCase(),
                        expiry: now.setMinutes(now.getMinutes() + 60) ,
                    }
                    localStorage.setItem("country", JSON.stringify(country));
                    change_country(country);
                }
            });
        }
        else{
            change_country(country);
        };
        function isJSON(str) {
            try {
                return (JSON.parse(str));
            } catch (e) {
                return false;
            }
        }
    $("a[data-href]").each(function (i, index) {
        $(this).click(function () {
            link = $(this).attr("data-href");
            window.location = link;
        });
    });
    $(".cmp_didomi").on("click", function(e) {
    
        e.preventDefault();
        Didomi.preferences.show();
    });
    function change_country(country){
        $("a[href^='https://wwws.airfrance.fr/exchange/deeplink']").each(function(){
        href=$(this).attr("href");
        var now = new Date();
        if (country && now.getTime() < country.expiry) {
            href=href.replace(/(country=).*?(&)/,'$1' + country.value+ '$2');
            $(this).attr('href',decodeURI(href));
        }
     });
     $("a[data-href^='https://wwws.airfrance.fr/exchange/deeplink']").each(function(){
        datahref=$(this).attr("data-href");
        var now = new Date();
        if (country && now.getTime() < country.expiry) {
            datahref=datahref.replace(/(country=).*?(&)/,'$1' + country.value+ '$2');
            $(this).attr('data-href',datahref);
        }
     });
    }

});
function check_if_in_view(element_class, add_new_class = '', callback = null) {
    jQuery(document).ready(function ($) {
        let window_height = $(window).height();
        let window_top_position = $(window).scrollTop();
        let window_bottom_position = (window_top_position + window_height);

        $.each(element_class, function () {
            let element = $(this);
            let element_height = element.outerHeight();
            let element_top_position = element.offset().top;
            let element_bottom_position = (element_top_position + element_height);

            //check to see if this current container is within viewport
            if ((element_bottom_position >= window_top_position) &&
                (element_top_position <= window_bottom_position)) {
                if (callback) {
                    callback();
                }

                element.addClass(add_new_class);
            }
        });
    });
}
function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function tryParseJSONObject (jsonString){
    try {
        var o = JSON.parse(jsonString);
        // Handle non-exception-throwing cases:
        // Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
        // but... JSON.parse(null) returns null, and typeof null === "object", 
        // so we must check for that, too. Thankfully, null is falsey, so this suffices:
        if (o && typeof o === "object") {
            return o;
        }
    }
    catch (e) { }

    return false;
};