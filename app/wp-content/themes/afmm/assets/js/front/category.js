jQuery(document).ready(function($) {

var is_mobile = false;

    if( $('#mobile-element').css('display')=='none') {
        is_mobile = true;       
    }

        const player = new Plyr('#player', {
            autoplay:true,
            controls:0,
            muted: true,
            loop:{
                active: true
            },
        });
    if (!is_mobile)
    {
        var lastScrollTop = 0;
        $(window).scroll(function(){
            var $img = $('#category-video-mea');
            var left =  parseInt($img.css('left'));
            var st = $(this).scrollTop();
                if (st > lastScrollTop){
                    if(left>120){
                        $img.css('left',function() {
                            return (left-2) +"px";
                    });
                    }
                } else {
                    if(left<290 ){
                    $img.css('left',function() {
                        return (left+2) +"px";
                    });
                    }
                }   
            lastScrollTop = st;
        });
    }
});
function setActiveNav() {
    jQuery(document).ready(function ($) {
        const path_groups = location.pathname.split('/').filter((v)=>v.length>0);
        // default nav_menu_lang = fr
        let nav_menu_lang = 'fr'
        if (path_groups[0].length == 2) {
            nav_menu_lang = path_groups[0];
            path_groups.shift();
        }
        const cur_loc_parent_cat = (path_groups[1])?path_groups[1]:'';
        const cur_loc_sub_cat = (path_groups[2])?path_groups[2]:'';

        if (cur_loc_parent_cat === "") return;
        let menu_items_links = $("ul#menu-navigation-"+nav_menu_lang+" > li > a");
        menu_items_links.each(function(){
            var href = $(this).attr('href');
            if(href.indexOf(cur_loc_parent_cat) !== -1){
                $(this).parent().addClass("active_cat");
                if (cur_loc_sub_cat === "") return;
                let cur_sub_li =  $(this).parent();
                let sub_menu_items_links = cur_sub_li.find(".sub-menu").children().find('a');
                sub_menu_items_links.each(function(){
                    var href = $(this).attr('href');
                    if(href.indexOf(cur_loc_sub_cat) !== -1){ 
                        $(this).attr('id', 'active_sub_cat');
                        return false;
                    }
                   
                });
               
            }
        });
    });
   
}
setActiveNav();