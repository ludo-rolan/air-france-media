<?php 

// Nom de dictionnaire de traduction
if (!defined('AFMM_TERMS')) {
    define('AFMM_TERMS', 'afmm');
}
require(STYLESHEET_DIR . '/include/functions/omeps.php');
require(STYLESHEET_DIR . '/include/functions/site-config.php');
require(STYLESHEET_DIR . '/include/functions/hooks.php');
require(STYLESHEET_DIR . '/include/functions/shortcodes.php');
require(STYLESHEET_DIR . '/include/functions/postsBySlugRest.php');
require(STYLESHEET_DIR . '/include/partners/partners.php');
require(STYLESHEET_DIR . '/include/functions/search.php');
require(STYLESHEET_DIR . '/include/functions/manage_emails.php');

if(is_admin()){
require(STYLESHEET_DIR . '/include/functions/filtre_post_type.php');
}
require(STYLESHEET_DIR . '/include/tax/tax.php');
require(STYLESHEET_DIR . '/include/cpt/cpt.php');
require(STYLESHEET_DIR . '/include/option/options.php');
if(file_exists(STYLESHEET_DIR . '/include/functions/cheetah.php')){
	require_once STYLESHEET_DIR . '/include/functions/cheetah.php';
}
require_once(STYLESHEET_DIR."/partners.php");









//HP
add_action('after_hp_podcast_carousel', function(){
?>
 <div id="megabanner_top">
    <?php 
    echo do_shortcode("[dfp id='masthead_haut']") ;

    ?>
</div>
<?php
    echo do_shortcode("[dfp id='native_mobile']") ;

});

//hp
add_action('after_hp_video_carouse', function(){
?>
 <div id="megabanner_bas">
    <?php echo do_shortcode("[dfp id='masthead_bas']") ;

    ?>
</div>
<?php
    echo do_shortcode("[dfp id='mobile_2']") ;

});

//HP
add_action('hp_after_block_styles', function(){
    echo do_shortcode("[dfp id='mobile_3']") ;
});
//single
add_action('after_the_content_all_type', function(){
	if($post_type != 'DIAPO') {
	    echo do_shortcode("[dfp id='mobile_2']");
	    //echo do_shortcode("[dfp id='native']");
	}
	    //echo do_shortcode("[dfp id='masthead_bas']");
});

add_action('footer_logo', function(){
	if(is_single()){
	    echo do_shortcode("[dfp id='masthead_bas']");
	}
});



//single
add_action('after_last_diapo_monetisation_mobile', function(){
	//if($i == 2){
		echo do_shortcode("[dfp id='mobile_2']") ;
		//echo do_shortcode("[dfp id='native']") ;
	//}
});


//recherche
add_action('after_post_searsh', function($i){
	if($i == 3){
		echo '<div>' ;
		echo do_shortcode("[dfp id='native']") ;
		echo do_shortcode("[dfp id='native_mobile']") ;
		echo '</div>' ;
	}elseif($i == 6){
		echo '<div>' ;
		echo do_shortcode("[dfp id='mobile_2']") ;
		echo '</div>' ;
	}
});


