jQuery(document).ready(function($) {
    $("#tri-adresse").on('change',function(){
        var params = new window.URLSearchParams(window.location.search);
        var val = $(this).val();
        if(val == 'pertinence'){
            url = '/mon-carnet-de-voyage/?par='+val ;
        }
        else{
            url ='/mon-carnet-de-voyage/';
        }
        window.location = url ;
    
    });

});