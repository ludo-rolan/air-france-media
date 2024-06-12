jQuery(document).ready(function ($) {
    $(".preview_adresse").on('click',function(){
        href=$(this).attr('data-link');
        window.open(href,'_blank');
    });
    var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
    var activated = false;
    $(".subnav > ul > li > a").each(function () {
        if ( ($(this).attr("href").includes(pgurl) && (pgurl != "") )){
            $(this).addClass("subnav-active");
            activated = true;
        }
    });
    if(activated == false){
        $("#subnav-default-active").addClass("subnav-active");
    }
});

function currentTime(timeZoneName) {
    jQuery(document).ready(function ($) {
        const timeDisplay = document.getElementById('destination_city_time');
        setInterval(function tick() {
            let time = new Date()
            time = time.toLocaleTimeString("en-GB", {timeZone: timeZoneName, hour: '2-digit', minute:'2-digit'})
            timeDisplay.innerText = time
        }, 1000);
    });
}