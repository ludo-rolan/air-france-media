<?php
class Maillage
{
	public static $header;
	public static $is_preprod;
	public static $afmm;
	public static $aftg;
	public static $response_code;
	public static $response_body;
	public static $media_url;
	public static $media_cat_url;
	public static $prefix_url_v2;

	static function register()
	{
		global $site_config;
		self::$header =  array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode('reworldmedia' . ':' . 'reworldmedia')
			)
		);
		self::$is_preprod = IS_PREPROD;
		self::$afmm = $site_config['afmm'];
		self::$aftg = $site_config['aftg'];
		self::$media_url="_embed=wp:featuredmedia&_fields=link,title,_links.wp:featuredmedia,_embedded.wp:featuredmedia";
		self::$media_cat_url="_embed&_fields=link,title,_links,_embedded,categories";
		//add_action('rest_api_init', [self::class, 'afmm_menu_route']);
	}
	// Création url site de base
	static function get_url_api_site($site){
		global $site_config;
		$site_config["langue_selectionner"]!="fr"?$add_lg=$site_config["langue_selectionner"].'/':$add_lg="";
		if (self::$is_preprod) {
			$link = $site['preprod'].$add_lg."/";
			$args = self::$header;
		} else {
			$link = $site['prod'].$add_lg."/";
			$args = [];
		}
		return [$link,$args];
	}

	//Construction d'url .
	static function cpt_link_build($link, $cpt,$is_cat=false)
	{
		$link .= "wp-json/wp/v2/$cpt?";
		$link .= $is_cat?self::$media_cat_url : self::$media_url;
		return $link;
	}
	//Construction d'url tax .
	static function tax_link_build($link, $tax)
	{
		$link .= "wp-json/wp/v2/$tax?";
		return $link;
	}
	// Envoyer la requette et recevoir la requette .
	static function process_request($link, $args, $is_json = true)
	{
		$response = wp_remote_request($link, $args);
		$response_code = wp_remote_retrieve_response_code($response);

		if ($response_code == 200) {
			if ($is_json) {
				return  json_decode(wp_remote_retrieve_body($response), true);
			} else {
				return wp_remote_retrieve_body($response);
			}
		}
		return [];
	}
	// Récupération des villes AFTG  dans la home page .
	static function afmm_maillage_villes($ids)
	{
		if (isset($ids)) {
			$params=self::get_url_api_site(self::$aftg);
			$params[0] = self::cpt_link_build($params[0], 'destination');
			$params[0] .= "&per_page=5&include=" . $ids;
			return self::process_request($params[0], $params[1]);
		} else {
			return [];
		}
	}
	/*static function aftg_maillage_menu($type)
	{
		global $site_config;
		$params=self::get_url_api_site(self::$afmm);
		$params[0] .= 'wp-json/afmm/'.$type."?lang=".$site_config["langue_selectionner"];
		return self::process_request($params[0], $params[1], false);
	}


	static function get_afmm_menu_api(WP_REST_Request $request )
	{
		global $site_config;
		$type=explode('/',$request->get_route())[2];
		self::caching_header_footer($site_config,$type,function () use ($type) {
            echo self::get_afmm_menu($type);
        });
		die;
	}

	static function afmm_menu_route()
	{   //wp-json/afmm/menu
		$menus = ['header', 'footer_1', 'footer_2','footer_3'];
		foreach ($menus as $menu) {
			register_rest_route('afmm', "/$menu", array(
				'methods' => 'GET',
				'callback' => 'Maillage::get_afmm_menu_api',

			));
		}
	}
	*/
	static function get_afmm_menu($type)
	{
		global $site_config;
			if($type=="header"){
				self::caching_header_footer($site_config,$type,function () {
					echo wp_nav_menu([
						'theme_location' => 'header-menu',
						'dept' => 2,
						'link_before' => '<span>',
						'link_after' => '</span>',
						'menu_class' => 'menu-header',
						'fallback_cb' => false,
						'walker' => '',
					]);
				});
			}
			else {
				if($type=="footer_1"){
					$class_css="d-flex justify-content-center bandeau_footer";
				}
				else {
					$class_css=$type;
				}

				self::caching_header_footer($site_config,$type,function () use ($type, $class_css) {
					echo wp_nav_menu(array('theme_location' =>
					$type, 'container' => false, 'menu_class' => $class_css));
				});
			}
	}

	// Récupération des article AFMM  dans la home page .
	static function aftg_maillage_article_par_destination($destination_id)
	{
		if ($destination_id) {
			$params=self::get_url_api_site(self::$afmm);
			$params[0] = self::cpt_link_build($params[0], 'posts',true);
			$params[0] .= "&per_page=3&destinations=".$destination_id;
			return self::process_request($params[0], $params[1]);
		} else {
			return [];
		}
	}

	// Récupération des article AFMM  récents .
	static function aftg_maillage_article_recents()
	{
		$params = self::get_url_api_site(self::$afmm);
		$params[0] = self::cpt_link_build($params[0], 'posts', true);
		$params[0] .= "&per_page=3";
		return self::process_request($params[0], $params[1]);
	}

	static function aftg_maillage_slug_external_id($destination_slug)
	{
		if ($destination_slug) {
			$params=self::get_url_api_site(self::$afmm);
			$params[0] = self::tax_link_build($params[0], 'destinations');
			$params[0] .= "slug=".$destination_slug;
			$response=self::process_request($params[0], $params[1]);
			if(count($response)){
				if(isset($response[0]['id'])){
					return $response[0]['id'];
				}
			}
		} else {
			return 0;
		}
	}

	static function caching_header_footer($site_config,$type, $callback)
	{
		$cache_key = $site_config['afcore_cache']['maillage']['key'] . '_' . $type . '_' . PROJECT_NAME;
		$cache_time = $site_config['afcore_cache']['maillage']['time'];
		echo_from_cache($cache_key, 'speedup', $cache_time, $callback);
	}
}
Maillage::register();
