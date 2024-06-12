<?php
global $partners_default ;


$partners_default = array(

	'didomi' => array(
		'desc'					=> 'DIDOMI',
		'class_name' 			=> 'didomi',
		'implementation' 		=> 'didomi.php',
		'callback' 				=> 'didomi_implementation',
		'action' => array('wp_head', 1),
		'is_tag' 				=> true,
		
	),
  'relay_42' => array(
		'desc'					=> 'RELAY 42',
		'class_name' 			=> 'relay_42',
		'implementation' 		=> 'relay_42.php',
		'callback' 				=> 'relay_42_implementation',
		'action' => array('wp_head', 1),
		'is_tag' 				=> true,
	),
	'google_analytics' => array(
		'desc'					=> 'GOOGLE ANALYTICS',
		'class_name' 			=> 'google_analytics',
		'implementation' 		=> 'google_analytics.php',
		'callback' 				=> 'google_analytics_implementation',
		'action' => array('wp_head', 1),
		'is_tag' 				=> true,
	),
	'dfp' => array(
		'desc' => 'DFP ( Google ) v3 desktop',
		'default_activation' => 1,
		'implementation' => 'dfp.php',
		'class_name' => 'dfp',
		'is_tag' => true,
		'callback' => 'init' ,
		'shortcodes' => array (
			'dfp'=> 'dfp_short_code',
		) ,
	),
);



add_action( 'wp', 'partners_core', 2000 );

if(is_admin()){
	add_action( 'init', 'partners_core' );
}

function partners_core() {
	global $partners, $site_config, $partners_default;
	
	if(is_feed()){
		return false;
	}
	$active = false;
	$name_option   = 'partners_activation' ;	

	$partners = apply_filters( 'init_partners', $partners );

	$name_option  = apply_filters( 'name_option', $name_option );
	$partners_activation = get_option( $name_option, array() );

	if( !empty($partners) ){
	foreach ( $partners as $key => $partner ) {
		
		$active = false;
		$shortcodes = isset( $partner['shortcodes'] ) ? $partner['shortcodes']  : null ;
		$cached_shortcodes = isset( $partner['cached_shortcodes'] ) ? $partner['cached_shortcodes']  : null ;
		$implementation = isset( $partner['implementation'] ) ? $partner['implementation']  : null ;

		if(empty( $partner['comportement_inverse']) || is_admin()){
			
				$active = isset( $partners_activation[ $key ] ) ? $partners_activation[ $key ]['active'] : $partner['default_activation'] ;
			
		}else{

			$active = false;
		}
	
		if(!empty($_GET['desactive_partners'])){
			$desactive_partners = $_GET['desactive_partners'] ;
			$desactive_partners = explode(',',$desactive_partners ) ;
			if(in_array($key, $desactive_partners)){
				$active = false ;
			}
		}

		if(!empty($_GET['no_pub']) && $_GET['no_pub'] == 'tags' ){
			if(!empty ($partner['is_tag'])){
				$active= false ;
			}
		}elseif(!empty($_GET['no_pub'])){
			$active= false ;
		}
		
		if(!empty($_GET['active_partners'])){
			$active_partners = $_GET['active_partners'] ;
			$active_partners = explode(',',$active_partners ) ;
			println_flush($active_partners);
			if(in_array($key, $active_partners)){
				$active = true ;
			}
		}
	
		if ( $active ) {
			
			if ( $implementation ) {
				
				require_once(locate_template( 'include/functions/implementations/' . $implementation , true ));
			}

			$config = isset( $partner['config'] ) ? $partner['config']  : null ;
			$class_name = isset( $partner['class_name'] ) ? $partner['class_name']  : null ;
			if ( $class_name ) {
				global $instance_partners;
				
				if(class_exists($class_name)){
					$instance = new $class_name ($key, $partner) ;
					$instance_partners[$key] = $instance ;
				}

			} else {
				if ( is_admin() ) {
					$action = isset( $partner['action_admin'] ) ? $partner['action_admin']  : null ;
					$callback = isset( $partner['callback_admin'] ) ? $partner['callback_admin']  : null ;
					$action_name = (is_array( $action )) ? $action[0]  : $action ;
					$priority = (is_array( $action ) && isset( $action[1] )) ? $action[1]  : 10 ;
					$accepted_args = (is_array( $action ) && isset( $action[2] )) ? $action[2]  : 1 ;
				} else {
					$action = isset( $partner['action'] ) ? $partner['action']  : null ;
					$callback = isset( $partner['callback'] ) ? $partner['callback']  : null ;
					$code = isset( $partner['code'] ) ? $partner['code']  : null ;
					$action_name = (is_array( $action )) ? $action[0]  : $action ;
					$priority = (is_array( $action ) && isset( $action[1] )) ? $action[1]  : 10 ;
					$accepted_args = (is_array( $action ) && isset( $action[2] )) ? $action[2]  : 1 ;
				}
				if ( $config ) {
					foreach ( $config as $key => $value ) {
						$site_config[ $key ] = $value ;
					}
				}

				if ( $callback ) {
					if ( $action_name ) {
						add_action( $action_name, $callback, $priority, $accepted_args );
					} else {
						call_user_func_array( $callback, array() );
					}
				}

				if ( ! is_admin() ) {
					if ( $code ) {
						add_action($action_name, function () use ( $code ) {
							echo $code ;
						}, $priority, $accepted_args);
					}
					if ( $shortcodes ) {
						foreach ( $shortcodes as $shortcode => $function ) {
							add_action('partners_core_ready', function () use ( $shortcode, $function ) {
								add_shortcode( $shortcode, $function );
							});
						}
					}
					
				}
			}

			if ( isset( $_GET['debug_partners'] ) ) {
				echo "<!-- Active Partner : $key -->" ;
			}
		} else {
			if ( $implementation ) {
				require_once(locate_template( 'include/functions/implementations/' . $implementation , true ));
				
			}

			if ( $cached_shortcodes ) {
				if ( $shortcodes ) {
					$shortcodes = array_merge( $shortcodes, $cached_shortcodes );
				} else {
					$shortcodes = $cached_shortcodes;
				}
			}

			if ( $shortcodes ) {
				foreach ( $shortcodes as $shortcode => $function ) {
					add_shortcode( $shortcode, '__return_false' );
				}
			}
		}
	}
	}
	do_action( 'partners_core_ready' );
}


add_filter('init_partners', function ( $partners ) {
	global $partners_default ;
	if ( !empty($partners) ) {
		foreach ( $partners as $key => &$partner ) {
			if ( isset( $partners_default[ $key ] ) ) {
				if(!isset($partners_default[ $key ]['default_activation'])){
					$partners_default[ $key ]['default_activation'] = false ;
				}
					$partner = wp_parse_args( $partner,   $partners_default[ $key ] );
			}
		}
	}
	return $partners ;
});