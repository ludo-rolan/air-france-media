<?php 
if (!defined('W3TC_CLEARCACHE')) {
    define('W3TC_CLEARCACHE', true);
}
if (!defined('AFMM_TERMS')) {
	define('AFMM_TERMS', 'afmm');
}

if( function_exists( 'icl_sitepress_activate' ) ){
	icl_sitepress_activate();
}
global $site_config_js ;

if(rw_is_mobile()){
	$site_config_js['nginx_mobile'] = 1 ;
}elseif(rw_is_tablet()){
	$site_config_js['nginx_tablet'] = 1 ;

}else{
	$site_config_js['nginx_desktop'] = 1 ;
}

add_action( 'after_setup_theme', 'my_theme_setup' );
function my_theme_setup(){
  load_theme_textdomain( AFMM_TERMS, get_template_directory() . '/languages' );
}
require(AF_THEME_DIR . '/include/option/omep.php');
require(AF_THEME_DIR . '/include/hooks.php');
require(AF_THEME_DIR . '/include/site-config.php');
require(AF_THEME_DIR . '/include/option/options.php');
require(AF_THEME_DIR . '/include/meta/metas.php');
require(AF_THEME_DIR . '/include/vol.php');
require(AF_THEME_DIR . '/include/tax/tax.php');
require(AF_THEME_DIR . '/include/functions/maillage.php');
require(AF_THEME_DIR . '/include/functions/shortcodes.php');
require(AF_THEME_DIR . '/include/functions/dfp_hooks.php');



require(AF_THEME_DIR . '/include/functions/partners-core.php');
require(AF_THEME_DIR . '/include/functions/partners_options.php');
require(AF_THEME_DIR . '/include/functions/rw_partners.php');
require(AF_THEME_DIR . '/include/functions/implementations/dfp.php');
require(AF_THEME_DIR . '/include/functions/autocomplete_destinations.php');
require(AF_THEME_DIR . '/include/functions/af_pagination.php');
require(AF_THEME_DIR . '/include/functions/json_feed.php');


add_action('save_post', 'save_posts_purge_cache',10,3);
function save_posts_purge_cache($post_id, $post, $update)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    if ($post->post_status == 'publish'){
        // TODO: purge cache sitemap
		purge_nginx_cache_for_post_and_categories_and_hp($post_id);
    } 
}


add_action('wp_footer', function(){
	echo do_shortcode("[dfp id='habillage']") ;
});


