jQuery(document).ready(function($) {
// create cookie if not found
if (getCookie("carnet_de_voyage")=="") {
    setCookie("carnet_de_voyage",'');
}
else{
    var mycookie = getCookie("carnet_de_voyage");
    ids =  mycookie.split(",");
    ids.forEach((item) => {
       element = $('.carnet_badge[data-id="'+item+'"]');
       if(element.length > 0){
        $(element[0]).addClass( "post_card_badge_minus" );
       }
       
      })
    

}



// set class on initialize
$(".carnet_badge").on('click', function(){
    
    id = $(this).attr('data-id');
    var keycookie = getCookie('carnet_de_voyage');
    ids =  keycookie.split(",");
    if(ids.length > 0){
    if(!ids.includes(id)){
        if (keycookie.length == 0){
            keycookie = id;
        }
        else{
            keycookie += ',' + id ;
        }
        setCookie("carnet_de_voyage",keycookie);
    }
    }
    if($( this).hasClass( "post_card_badge_minus" )){
        $(this).removeClass("post_card_badge_minus");
        if(keycookie.includes(id + ',')){
            keycookie = keycookie.replace(id + ',', '');
        }else if(keycookie.includes(','+ id )){
            keycookie = keycookie.replace(','+id , '');
        }
        else{
            keycookie = keycookie.replace(id , '');}
        
        
        setCookie("carnet_de_voyage",keycookie);
    }
    else{
        $(this).toggleClass("post_card_badge_minus");
        
    }
    adresses = keycookie.split(',');
    nbr_adresses = adresses.length;
    if(nbr_adresses< 0){
        $('.nav-desktop-carnet a').text(0);
        $('.nav-mobile-carnet a').text(0);

    }
    else{
        if(keycookie==''){
            $('.nav-desktop-carnet a').text(nbr_adresses- 1);
            $('.nav-mobile-carnet a').text(nbr_adresses- 1);

        }
        else{
            $('.nav-desktop-carnet a').text(nbr_adresses);
            $('.nav-mobile-carnet a').text(nbr_adresses);

        }
    }
    
 
});

//  read cookie
carnet_voyage_cookie = getCookie("carnet_de_voyage");
if ($('.carnet_container').length) {
    var url = new URL(window.location.href);
    $.ajax({
        // avant : on utilise la data-search script (lentement sur le site + on a pas besoin de ce script dans cette page)
        // maintenant : on génére l'url de admin ajax depend le besoin 
        url: url.origin + '/' + 'wp-admin/admin-ajax.php',
        type: 'POST',
        data: {
            action: 'carnet_voyage',
            ids: carnet_voyage_cookie,
        },
        success: function (data) {
            $('.carnet_container').html(data);
        }
    
    });
}

// carnet header count
adresses = carnet_voyage_cookie.split(',');
if(carnet_voyage_cookie==''){
    $('.nav-desktop-carnet a').text(adresses.length - 1);
    $('.nav-mobile-carnet a').text(adresses.length - 1);

}
else{
    $('.nav-desktop-carnet a').text(adresses.length);
    $('.nav-mobile-carnet a').text(adresses.length);

}



// delete cookie
$("#carnet_voyage_block_0").on('click',function(){
    setCookie("carnet_de_voyage",'','Thu, 01 Jan 1970 00:00:01 GMT');
    window.location.reload();
});


// set class on initialize
$(".carnet_badge_adresse").on('click', function(){
    
    id = $(this).attr('data-id');
    var keycookie = getCookie('carnet_de_voyage');
    ids =  keycookie.split(",");
    if(ids.length > 0){
    if(!ids.includes(id)){
        if (keycookie.length == 0){
            keycookie = id;
        }
        else{
            keycookie += ',' + id ;
        }
        setCookie("carnet_de_voyage",keycookie);
    }
    }
    adresses = keycookie.split(',');
    nbr_adresses = adresses.length;
    if(nbr_adresses< 0){
        $('.nav-desktop-carnet a').text(0);
        $('.nav-mobile-carnet a').text(0);

    }
    else{
        if(keycookie==''){
            $('.nav-desktop-carnet a').text(nbr_adresses- 1);
            $('.nav-mobile-carnet a').text(nbr_adresses- 1);

        }
        else{
            $('.nav-desktop-carnet a').text(nbr_adresses);
            $('.nav-mobile-carnet a').text(nbr_adresses);

        }
    }
 
});



});


