<?php
class  Post_Helper {
	// update post thumbnail
	public static function update_post_thumbnail( $post_id, $post_thumbnail_id ) {
		if ( ! $post_id ) {
			return;
		}
		if ( ! $post_thumbnail_id ) {
			delete_post_thumbnail( $post_id );
			return;
		}
		$post_thumbnail_id = absint( $post_thumbnail_id );
		if ( ! $post_thumbnail_id ) {
			return;
		}
		// check if the post thumbnail is already set
		$post_thumbnail_id_old = get_post_thumbnail_id( $post_id );
		if ( $post_thumbnail_id_old == $post_thumbnail_id ) {
			return;
		}
		// check if the post thumbnail exists
		$post_thumbnail_id_exists = get_post( $post_thumbnail_id );
		if ( ! $post_thumbnail_id_exists ) {
			return;
		}
		// check if the post thumbnail is already set to the post
		if ( $post_thumbnail_id_old ) {
			delete_post_thumbnail( $post_id );
		}
		// set the post thumbnail
		set_post_thumbnail( $post_id, $post_thumbnail_id );
		return $post_thumbnail_id;
	}

	// set post thumbnail by url
	public static function set_post_thumbnail_by_url( $post_id, $post_thumbnail_url, $post_thumbnail_description = '' , $date = '' ) {
		if ( ! $post_id ) {
			return;
		}
		if ( ! $post_thumbnail_url ) {
			return;
		}
		$attachment_id = Media_Helper::download_attachment_by_url( $post_thumbnail_url, $post_thumbnail_description, $date );
		// check if the post thumbnail is already set
		$post_thumbnail_id_old = get_post_thumbnail_id( $post_id );
		if ( $post_thumbnail_id_old == $attachment_id ) {
			return $attachment_id;
		}
		return self::update_post_thumbnail( $post_id, $attachment_id );
	}
	static function print_site_config_js(){ 
		global $site_config_js, $site_config, $devs ; 


		if(is_single()){
			$site_config_js ['post_id'] = get_the_ID() ;
		}

		if(IS_PREPROD && isset($site_config["test_google_analytics_id"])){
			$site_config_js["google_analytics_id"] = $site_config["test_google_analytics_id"];
			$site_config_js["google_analytics_id"] = apply_filters('google_analytics_id', $site_config["test_google_analytics_id"] ); 

		}else{
			$site_config_js["google_analytics_id"] = $site_config["google_analytics_id"];
			if (isset($site_config["google_analytics_id"] ))
				$site_config_js["google_analytics_id"] = apply_filters('google_analytics_id', $site_config["google_analytics_id"] ); 

		}
		$site_config_js["reworld_async_ads"] = get_option('reworld_async_ads', 1);

		$site_config_js['url_template'] = get_template_directory_uri();

		if(is_array($devs) && count($devs)){
			foreach ($devs as $key => $value) {
				if(IS_PREPROD){
					$site_config_js ['devs'] [$key] = true ;
				}else{
					$site_config_js ['devs'] [$key] = false ;
				}
			}
		}
		$site_config_js['is_preprod'] = 0;
		if(defined('_IS_LOCAL_') or defined('_IS_DEV_') or defined('_IS_PREPROD_')){
			$site_config_js['is_preprod'] = 1;
		}

		if(isset($_GET['mode_test_on'])){
			$site_config_js['is_preprod'] = true;
		}

		if(isset($_GET['mode_test_off'])){
			$site_config_js['is_preprod'] = false;
		}


		

		$site_config_js['lang'] = strtolower(substr(get_locale(), 0, 2)) ;
		
		/*if($msg_cookie = get_param_global('msg_accepte_cookies')){
			$site_config_js['msg_accepte_cookies'] = sprintf(__($msg_cookie, REWORLDMEDIA_TERMS),get_param_global('link_page_politique')) ;
		} 
		else {
			if(is_dev('network_changement_bandeau_cookies_117553065')){
				if( is_dev('changement_wording_cookies_157030185') ){
					$site_config_js['msg_accepte_cookies'] = sprintf(__('En naviguant sur ce site, vous acceptez la politique d\'utilisation des cookies. <a href="%s" target="_blank"> En savoir plus </a><div>Si vous ne souhaitez pas que vos cookies soient utilisés par nos partenaires vous pouvez <a href="http://optout.networkadvertising.org/?c=1#!" target="_blank">Cliquer ici</a></div>', REWORLDMEDIA_TERMS),get_param_global('link_page_politique'));
				}else{
					$site_config_js['msg_accepte_cookies'] = sprintf(__('En naviguant sur ce site, vous acceptez la politique d\'utilisation des cookies. <a href="%s" target="_blank"> En savoir plus </a>', REWORLDMEDIA_TERMS),get_param_global('link_page_politique')) ;
				}
			}else{
				$site_config_js['msg_accepte_cookies'] = sprintf(__('Nous utilisons des cookies afin de vous fournir une expérience utilisateur fluide et adaptée à vos centres d’intérêts. En naviguant sur ce site, vous acceptez la politique d\'utilisation des cookies. <a href="%s" target="_blank"> En savoir plus </a>', REWORLDMEDIA_TERMS),get_param_global('link_page_politique')) ;
			}
		}
		*/

		

		//wp_localize_script('jquery', 'site_config_js', $site_config_js);
		echo '<script type="text/javascript"> site_config_js='. json_encode( $site_config_js ) .' </script> ' ;

		do_action('after_print_site_config_js') ;
	}

	static function rw_get_post_types() {
		$return = get_post_types( array('public' => true)) ;
		$excluded_post_types = ['attachment', 'nltemplate', 'newsletter'];
		$return = array_diff($return, $excluded_post_types);
		return $return ;
	 }
	

}