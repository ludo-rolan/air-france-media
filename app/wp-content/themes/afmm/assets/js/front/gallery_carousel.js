jQuery(document).ready(function($) {
    Slider = function(sliderIndex) { this.initialize.apply(this, arguments, sliderIndex) }
    Slider.prototype = {

        initialize: function(slider, sliderIndex) {
            this.container = slider;
            this.is_monitisation = $(this.container).hasClass('gallery-slider-monetisation');
            this.image_caption_container = this.is_monitisation ? ' p' : '';
            this.image_title_container = this.is_monitisation ? ' h3' : '';
            this.image_description_container = this.is_monitisation ? ' span' : '';
            const ua = navigator.userAgent;
            // The <ul> containing all <li>’s of images
            if (!(this.is_monitisation && /Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua))) {
                $(this.container).wrapInner("<ul class='blocks-gallery-grid ul-"+sliderIndex+"'></ul>");
            }
            else {
                $(this.container).find('.wp-block-image').wrap("<ul class='blocks-gallery-grid ul-"+sliderIndex+"'></ul>");
            }
            this.ul = slider.querySelector('ul');
            // wrap the <ul> in the slider container div
            $(this.ul).wrap('<div class="gallery-slider-container gallery-slider-container-'+sliderIndex+'"></div>');
            // all <li>’s
            $( ".ul-"+sliderIndex+" .wp-block-image" ).wrap( "<li class='blocks-gallery-item'></li>" );
            this.li = this.ul.getElementsByTagName('li');
            this.slider = $(".gallery-slider-container-"+sliderIndex);
            if(this.li.length > 1) {
                // Insert the left arrow div
                $(this.ul).before('<div class="gallery-slider-arrow-left gallery-slider-arrow-left-'+sliderIndex+'"><img src="'+arrows.leftArrow+'"></div>');
                // Insert the right arrow div
                $(this.ul).after('<div class="gallery-slider-arrow-right gallery-slider-arrow-right-'+sliderIndex+'"><img src="'+arrows.rightArrow+'"></div>');
            }
            this.leftArrow = $('.gallery-slider-arrow-left-'+sliderIndex);
            this.rightArrow = $('.gallery-slider-arrow-right-'+sliderIndex);

            // Set dimensions on start
            if (!this.is_monitisation || !/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) 
            {
                this.setDimensions();
            }
            else {
                figure = this.li[0].getElementsByTagName('figure')[0];
                figure.className='';
                figure.style='';
            }

            this.currentIndex = 0
        },

        // make <ul> as large as all <li>’s, and give <li>’s full width of the slider
        setDimensions: function() {
            single_li_width = $(this.slider).width();
            this.ul.style.width = (single_li_width * this.li.length) + 'px';
            
            for(i=0; i<this.li.length; i++) {
                this.li[i].style.width = single_li_width + 'px';
                figure = this.li[i].getElementsByTagName('figure')[0];
                figure.className='';
                figure.style='';
            }
            
        },

        // given an index : move the slider to show the next/previous image based on the index,
        // and disactivate the arrows if in first or last image.
        goTo: function(index) {
            if(index == 0 || this.currentIndex == 0) {
                $(this.leftArrow).toggleClass('gallery-slider-arrow-blur');
                if(this.is_monitisation) {
                    $('.gallery-slider-monetisation-prev').toggleClass('gallery-slider-arrow-blur');
                }
            }
            if(index == this.li.length - 1 || this.currentIndex == this.li.length - 1) {
                $(this.rightArrow).toggleClass('gallery-slider-arrow-blur');
                if(this.is_monitisation) {
                    $('.gallery-slider-monetisation-next').toggleClass('gallery-slider-arrow-blur');
                }
            }
            this.ul.style.left = '-' + (100 * index) + '%';
            this.currentIndex = index;
            history.replaceState({}, null, "#item="+(this.currentIndex+1));
            dfp_refresh_all_ads();
            let url_with_hashtag=window.location.pathname + window.location.search+window.location.hash;
            pageview_GA(url_with_hashtag);
            send_GA('viewed_diapo', url_with_hashtag, 'item-'+(this.currentIndex+1));
        },

        goToPrev: function(i) {
            if(arrows.is_diapo && arrows.is_scroll_enabled){
                $(window).scrollTop(0);
            }
            this.goTo(this.currentIndex - 1);
            $('.gallery-slider-count-current-'+i).text(DeuxDigits(this.currentIndex + 1));
            caption = $(".gallery-slider-container-"+ i).find('figcaption'+this.image_caption_container)[this.currentIndex];
            caption_content = $(caption).length ? caption.innerHTML : '';
            $('.blocks-gallery-caption-'+i+this.image_caption_container).html(caption_content);
            title = $(".gallery-slider-container-"+ i).find("figcaption"+this.image_title_container)[this.currentIndex];
            caption_title = $(title).length ? title.innerHTML : '';
            $('.blocks-gallery-caption-'+i+this.image_title_container).html(caption_title);
            // l'ajout de description au diapo
            description = $(".gallery-slider-container-"+ i).find("figcaption"+this.image_description_container)[this.currentIndex];
            caption_description = $(description).length ? description.innerHTML : '';
            $('.blocks-gallery-caption-'+i+this.image_description_container).html(caption_description);

        },

        goToNext: function(i) {
            if(arrows.is_diapo && arrows.is_scroll_enabled){
                $(window).scrollTop(0);
            }
            this.goTo(this.currentIndex + 1);
            $('.gallery-slider-count-current-'+i).text(DeuxDigits(this.currentIndex + 1));
            caption = $(".gallery-slider-container-"+ i).find("figcaption"+this.image_caption_container)[this.currentIndex];
            caption_content = $(caption).length ? caption.innerHTML : '';
            $('.blocks-gallery-caption-'+i+this.image_caption_container).html(caption_content);
            title = $(".gallery-slider-container-"+ i).find("figcaption"+this.image_title_container)[this.currentIndex];
            caption_title = $(title).length ? title.innerHTML : '';
            $('.blocks-gallery-caption-'+i+this.image_title_container).html(caption_title);
            // l'ajout de description au diapo
            description = $(".gallery-slider-container-"+ i).find("figcaption"+this.image_description_container)[this.currentIndex];
            caption_description = $(description).length ? description.innerHTML : '';
            $('.blocks-gallery-caption-'+i+this.image_description_container).html(caption_description);
        }
    }

    sliders = []
    sliderIndex = 0;
    // foreach slider in the post, create a Slider object
    $('.wp-block-gallery').each(function() {
        sliders.push(new Slider(this, sliderIndex));
        sliderIndex++;
    });

    for (let i = 0; i < sliders.length; i++) {
        let count_line_class = sliders[i].is_monitisation ? 'gallery-slider-count-line-monetisation' : '';
        let count_container_class = sliders[i].is_monitisation ? ' pb-0 mt-3' : '';
        let counter_div = '<div class="gallery-slider-count-container-'+i+' gallery-slider-count-container'+count_container_class+'">' +
            '<span class="gallery-slider-count-current gallery-slider-count-current-'+i+'">'+DeuxDigits(sliders[i].currentIndex + 1)+'</span>' +
            '<div class="gallery-slider-count-line '+count_line_class+'">' +
            '</div><span class="gallery-slider-count-total">'+DeuxDigits(sliders[i].li.length)+'</span>' +
        '</div>';
        if(sliders[i].li.length > 1) {
            // blur left arrow on start
            $(sliders[i].leftArrow).addClass('gallery-slider-arrow-blur');
            // add a div to show the current image and total images.
            if(sliders[i].is_monitisation) {
                $(sliders[i].slider).before(counter_div);
            }
            else {
                $(sliders[i].slider).after(counter_div);
            }
            // On left arrow click
            sliders[i].leftArrow.click(function () {
                sliders[i].goToPrev(i);
            });
            // On right arrow click
            sliders[i].rightArrow.click(function () {
                sliders[i].goToNext(i);
            });
    
            if(sliders[i].is_monitisation) {
                // On prev button click
                $('.gallery-slider-monetisation-prev').click(function () {
                    sliders[i].goToPrev(i);
                });
                // On next button click
                $('.gallery-slider-monetisation-next').click(function () {
                    sliders[i].goToNext(i);
                });
            }
        }

        // Initialise image legend
        if(sliders[i].is_monitisation) {
            caption = $(".gallery-slider-container-"+ i).find("figcaption")[0];
            caption_content = $(caption).length ? caption.innerHTML : '';
            $(sliders[i].slider).before('<div class="blocks-gallery-caption-'+i+' blocks-gallery-caption">'+ caption_content +'</div>');
        }
        else {
            if(sliders[i].li.length === 1) {
                $(sliders[i].slider).after(counter_div);
            }
            caption = $(".gallery-slider-container-"+ i).find("figcaption")[0];
            caption_content = $(caption).length ? caption.innerText : '';
            $(sliders[i].slider).after('<div class="blocks-gallery-caption-'+i+' blocks-gallery-caption">'+ caption_content +'</div>');
        }
        $(sliders[i].slider).find('figcaption').css('display', 'none');

        var slidingSize = document.querySelector('.post-content');
        new ResizeObserver(() => {
            sliders[i].setDimensions();
        }).observe(slidingSize);
    }
    
    // Show number in tow digits format, example : 01, 02, ...
    function DeuxDigits(nbr) {
        return (nbr < 10 ? '0' : '') + nbr;
    }


});
jQuery(document).ready( function($){
    var url_page = (window.location).toString(),
    str_split="#",
    scrolling_up = false,
    $back_to_top = $('#linear_top'),
    scroll_history = [];
    var diapImgs = $('.diapo_monetisation_mobile');



    // permet de rajouter lors du scroll l'uri de l'image dans la barre des liens (ex : #uri-de-l-image)
    jQuery(document).scroll(function () {
        diapImgs.each(function (i, e) {
            var top = window.pageYOffset;
            var distance = top - $(this).offset().top;

            if (distance < 40 && distance > -40 ) {
                // refresh ads
                    var id = $( this ).attr( 'data-id');
                    var item_id = $( this ).attr( 'id' );
                    var url_page_tab = url_page.split( str_split );
                    var url_page_new = url_page_tab[0]  + "#item=" + item_id ;
                    if ( "pushState" in history  && !scrolling_up ) {
                            history.pushState( null, 'Diaporama Linéaire', url_page_new );
                        
                        $(document).trigger('linear_gallery_item_changed' , [i]);
                        if( window.location.hash != "" ) scroll_history.push(window.location.hash);
                        // refresh google analytics tracking
                        if ( site_config_js && site_config_js.reworld_async_ads == 1 && window.last_refres !== e ) {
                            var a = document.createElement( 'a' );
                            a.href = url_page_new;
                            var only_path_and_hash = a.pathname + a.hash;
                            let url_with_hashtag=window.location.pathname + window.location.search+window.location.hash;
                            pageview_GA(url_with_hashtag);
                            setTimeout(function(){
                                send_GA( 'mobile_diapo_linear_viewed', url_page_tab[0], id );
                            }, 3000);
                        }
                    }
                

                if ( typeof refresh_ads == 'function' && self.sas_ajax && window.last_refres !== e ) { 
                    refresh_ads();
                }

                if(window.last_refres !== e ){
                    var rafraichir_pub = false ;
                     if( window.last_refres ){
                         rafraichir_pub = false ;
                     }
                    
                    $(document).trigger('change_item' , [i, rafraichir_pub]);	
                }
            
                window.last_refres = e ;

                return false;
            }
        });
        $last_diapo_item = $('.diapo_linear .diaporama-infos').last();
        if( $last_diapo_item.length>0 ){
            last_diapo_item_height = $last_diapo_item.offset().top;
            if( site_config_js.diapo_lineaire_retour_arriere && $(window).scrollTop() > last_diapo_item_height ){
                if( $back_to_top.hasClass('hide') ) $back_to_top.removeClass('hide');
            }else if( !$back_to_top.hasClass('hide') ){
                $back_to_top.addClass('hide');
            }
        }
    });
if( site_config_js.diapo_lineaire_retour_arriere ){
    // Back to top linear diapo button
    $back_to_top.on('click', function(){
        scrolling_up = true;
        $('body,html').animate(
            { scrollTop: 0 },
            {
                duration: 500,
                complete: function(){
                    if( typeof window.history.go !== "undefined" ) {
                        window.history.go( -scroll_history.length );
                    }
                    if( scroll_history.length>0 ){
                        scroll_history = [];
                        scrolling_up = false;
                    }
                }
            }
        );
    });
    // Listening to history state
    window.addEventListener('popstate',function(e){ 
        if( scroll_history.length>0 && scroll_history[scroll_history.length-2] == window.location.hash ){
            // Navigation going backward
            scroll_history.pop();
        }else if( window.location.hash != "" && scroll_history.length !== 0 ){
            // Navigation going forward
            scroll_history.push(window.location.hash);
        }
    });
}


if( site_config_js.zoom_images_diapo_linear ){
    $('.diaporama-infos .diaporama-image img').featherlight({
        loading: 'Loading...', 
        openSpeed:    300,
        closeSpeed:   300
    });
}
});