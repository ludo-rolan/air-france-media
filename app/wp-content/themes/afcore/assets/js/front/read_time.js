jQuery(document).ready(function($) {
    $('.read-time-header-block').hide();
    $('header').removeClass('fixed-top');
            $(window).scroll(function(){
                if($('.read-time-header')[0]){
                    myList = $('header #navbarMobileContent ul');
                    myList.hide();
                    
                    var pixels = $(document).scrollTop();
                    var pageHeight = $(document).height() - $(window).height();
                    var progress = 100 * pixels / pageHeight;
                    if(progress==0) {
                        myList.show();
                        $('.read-time-header-block').hide();
                        $('header').removeClass('fixed-top');
                    }else{
                        $('.read-time-header-block').show();
                        $('.nav-mobile-search-container').hide();
                        $('header').addClass('fixed-top');
                    }
                    $("div.progress").css("width", progress + "%");
                    $(".skin_size_1000 .progress").css("width", progress*10);
                }
            });
    });