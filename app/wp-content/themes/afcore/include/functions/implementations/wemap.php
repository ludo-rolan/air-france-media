<?php
class Wemap extends rw_partner
{
	private $token_id = "at5464f5f48843a8.67809823";
	private $api_url = "https://api.getwemap.com/v3.0/";
	private $emmid = "";

	public function __construct($name, $partner)
	{
		parent::__construct($name, $partner);
		if (get_omep_val('wemap_use_api_prod_ids_0672')) {
			$this->emmid = (ICL_LANGUAGE_CODE == "fr") ? "4166" : "4173";
		} else {
			//https://pro.getwemap.com/#/app/livemaps/20156
			$this->emmid = "20156";
		}
	}
	/**
	 * Ce script est inséré sur l’ensemble des pages
	 */
	function wemap_implementation()
	{
		$af_map = [];
		$loc_info = [];
		// adresse
		if (get_post_type() == 'adresse') {
			// default view
			$loc = get_post_meta(get_the_ID(), 'geo_coordonees', true);
			$localisation = explode(',', $loc);
			$latitude_addr = $localisation[0];
			$longitude_addr = $localisation[1];

			// custom view
			$custom_view = get_post_meta(get_the_ID(), 'localisation_map', true);
			if ($custom_view != '') {
				$loc_info = [
					'localisation_map' => str_contains($custom_view, '\\') ? json_decode($custom_view) : $custom_view,
				];
			} elseif ($localisation != '') {
				$loc_info = [
					'localisation_longitude' => $longitude_addr,
					'localisation_latitude' => $latitude_addr
				];
			}
			$this->front_or_admin($af_map, $loc_info);
		}

		// destination
		elseif (get_post_type() == 'destination') {
			$localisation = get_post_meta(get_the_ID(), 'localisation', true);
			if ($localisation != '' && $localisation['localisation_map'] != null) {
				$loc_info = [
					'localisation_map' => $localisation['localisation_map'],
				];
			} else {
				$loc_info = [
					'localisation_longitude' => $localisation['localisation_longitude'],
					'localisation_latitude' => $localisation['localisation_latitude'],
				];
			}
			$this->front_or_admin($af_map, $loc_info);
		}

		// aux alentours
		if (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == 'aux_alentours') {
			$to_load = true;
			$localisation = get_term_meta($_REQUEST['tag_ID'], 'localisation_map', true);
			// localisation isset
			if ($localisation) {
				$loc_info = [
					'localisation_map' => $localisation
				];
			} else {
				$localisation_longitude = get_term_meta($_REQUEST['tag_ID'], 'localisation_longitude', true);
				$localisation_latitude = get_term_meta($_REQUEST['tag_ID'], 'localisation_latitude', true);
				$loc_info = [
					'localisation_longitude' => $localisation_longitude,
					'localisation_latitude' => $localisation_latitude,
				];
			}
			$this->front_or_admin($af_map, $loc_info);
		}
	}

	function front_or_admin($af_map, $loc_info)
	{
		global $pagenow;
		$af_map = [
			'emmid' => $this->emmid,
			'localisation' => $loc_info,
			'token' => $this->token_id
		];
		if ((is_admin() && in_array($pagenow, ['post-new.php', 'post.php'])) || (is_admin() && $pagenow == 'term.php' && isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == 'aux_alentours')) {
			$af_map = array_merge($af_map, ['title' => 'af-admin-wemap', 'dossier' => 'admin']);
			wp_enqueue_script('afcore-utils', AF_THEME_DIR_URI . '/assets/js/lib/afcore-utils.js', array('jquery'), CACHE_VERSION_CDN, true);
		} elseif (is_singular('destination') || is_singular('adresse') || is_tax('aux_alentours')) {
			// front
			$af_map = array_merge($af_map, ['title' => 'af-wemap', 'dossier' => 'front']);
		}
		wp_enqueue_script('af-wemap-sdk', 'https://livemap.getwemap.com/js/sdk.min.js', array(), CACHE_VERSION_CDN, true);
		wp_register_script($af_map['title'], AF_THEME_DIR_URI . '/assets/js/' . $af_map['dossier'] . '/' . $af_map['title'] . '.js', array('jquery', 'af-wemap-sdk', 'afcore-utils'), CACHE_VERSION_CDN, true);
		wp_localize_script($af_map['title'], 'af', $af_map);
		wp_enqueue_script($af_map['title']);
	}
	
}
