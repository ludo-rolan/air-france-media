jQuery(document).ready(function($) {
    
    let guid_link = (window.location.href).split('?')[0];
    $('#infosPratiques_buttons_blue').attr('href',guid_link);
    function offsetAnchor() {

        var url_page = window.location.href
        const after_hashtag = url_page.split('#').pop(); 
        $('.infosPratiques_menu a').each(function() {
            if ($(this).attr('href')  ===  ("#"+after_hashtag)) {
                $(this).addClass('active_inf');
                $(this).parents('li').addClass("hovered_li");

            }else{
                $(this).removeClass('active_inf');
                $(this).parents('li').removeClass("hovered_li");
            }
        });

        if(location.hash.length !== 0) {
            window.scrollTo(window.scrollX, window.scrollY - 100);
        }
        
    }
    window.addEventListener("hashchange", offsetAnchor);
    window.setTimeout(offsetAnchor, 250);
    
    $('.savoir-single-curtime').html(function(){
        var content= $('.savoir-single-curtime').text();  
        var currentDate = new Date();
        var GMTMinutes = parseInt(currentDate.getUTCMinutes());
        var GMTHours = parseInt(currentDate.getUTCHours());
        var sign=content[0];
        var timeZoneHeure=parseInt(sign+content[1]+content[2]);
        var timeZoneMinute=parseInt(sign+content[4]+content[5]);
        var currentTimeHeure=GMTHours+timeZoneHeure;
        var currentTimeMenute=GMTMinutes+timeZoneMinute;
        if (currentTimeMenute<10){
            currentTimeMenute="0"+currentTimeMenute;
        }
        return currentTimeHeure+":"+currentTimeMenute;  
});
});