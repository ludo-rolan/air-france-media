jQuery(document).ready(function ($) {
    $('.submenu-ul li').each(function(i){
        let id = $('a',this).attr('href');
        if(!$(id).length){
            this.remove();
        }
    });
    $('#destination_city_time').text(function(){
        var content= $('#destination_city_time').text();  
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