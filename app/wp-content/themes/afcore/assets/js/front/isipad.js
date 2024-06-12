jQuery(document).ready(function ($){
    var isHomePage = window.location.pathname == '/' ? true : false;
    var home_header_exist = $('#homeheader').length > 0 ? true : false;
    var not_home_header_exist = $('#nothomeheader').length > 0 ? true : false;
    var is_ipad = screen.width < 850 ;

    if(isHomePage && home_header_exist && not_home_header_exist && is_ipad){
        $('#nothomeheader').hide();
        $('#Calque_1').hide();
    }
})