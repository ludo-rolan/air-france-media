<?php
/**
 *
 */
class rw_partner {
	
    private $name;
    private $config;
    private $callback;

	public function __construct( $name, $partner ) {
		$this->name  = $name ;
		$this_ = $this ;
		if ( isset( $partner['config'] ) ) {
			$this->config  = $partner['config'] ;
		}
		$shortcodes = isset( $partner['shortcodes'] ) ? $partner['shortcodes']  : null ;
		$cached_shortcodes = isset( $partner['cached_shortcodes'] ) ? $partner['cached_shortcodes']  : null ;

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

		if ( $callback ) {
			if ( $action_name ) {
				add_action( $action_name, array( $this, $callback ), $priority, $accepted_args );
			} else {
				// call_user_func_array(array($this, $callback), array());
				$this->$callback();
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
					add_action('partners_core_ready', function () use ( $this_, $shortcode, $function ) {
						add_shortcode( $shortcode, array( $this_, $function ) );
					});
				}
			}
		
		}
	}

	public function action_admin() {
	}

	public function action_front() {
	}

	public function get_param( $param, $default_if_not_set = '' ) {
		$v = (isset( $this->config[ $param ] )) ? $this->config[ $param ] : $default_if_not_set ;
		return $v;
	}

	/**
	 * Supprimer les tags pubs données en paramètre
	 * @param array $tags tableau de tags à supprimer
	 * @return void
	 */
	static function remove_selected_tags_pubs( $tags ) {
		foreach ( $tags as $tag ) {
			add_filter( 'partner_filter_' . $tag, '__return_false' );
		}
	}
}

?>