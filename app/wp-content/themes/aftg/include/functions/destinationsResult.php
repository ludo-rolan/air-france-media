<?php

class destinationsResult
{
	public static function register()
	{
		add_action('rest_api_init', [self::class, 'filters_result']);
		//ZAKARIA
		add_action('rest_api_init', [self::class, 'autocomplete_items']);
		//AYOUB
		add_action('rest_api_init', [self::class, 'regions']);
	}

	//AYOUB
	function regions()
	{
		register_rest_route('aftg', "/regions/(?P<lg>[a-zA-Z]+)", array(
			'methods' => 'POST',
			'callback' => 'destinationsResult::getallregions',
		));
	}

	//AYOUB
	function getallregions(WP_REST_Request $request)
	{
		echo self::region_result($request->get_params());
	}

	function filters_result()
	{
		register_rest_route('aftg', "/get_filters_result/", array(
			'methods' => 'POST',
			'callback' => 'destinationsResult::filters_result_callback',
		));
	}

	function filters_result_callback(WP_REST_Request $request)
	{
		self::get_filters_result();
	}

	function autocomplete_items()
	{
		register_rest_route('aftg', "/get_autocomplete_items/", array(
			'methods' => 'GET',
			'callback' => 'destinationsResult::get_all_autocomplete_items_callback',
		));
	}

	function get_all_autocomplete_items_callback(WP_REST_Request $request)
	{
		self::get_all_autocomplete_items();
	}

	//ZAKARIA
	function get_all_autocomplete_items()
	{
		global $site_config;

		$cache_ajax_autocomplete_result_key = $site_config['aftg_cache']['ajax_autocomplete_result']['key'];
		$cache_ajax_autocomplete_result_time = $site_config['aftg_cache']['ajax_autocomplete_result']['time'];
		$destination_json = get_data_from_cache($cache_ajax_autocomplete_result_key, 'hp_bandeau_image', $cache_ajax_autocomplete_result_time, function ()  {

			global $sitepress;
			$current_language = apply_filters('wpml_current_language', NULL);
			$sitepress->switch_lang($current_language);
			$terms = get_terms('destinations');

			$region_terms_ids = [];
			$country_terms_ids = [];

			$region_terms = [];
			$country_terms = [];
			$city_terms = [];


			foreach ($terms as $term) {
				if ($term->parent == 0) {
					$region_terms_ids[] = $term->term_id;
				}
			}
			foreach ($terms as $term) {
				if ($term->parent != 0 && in_array($term->parent, $region_terms_ids)) {
					$country_terms_ids[] = $term->term_id;
					$country_terms[] = [
						'label' => $term->name,
						'link' => get_term_link($term),
					];
				}
			}
			foreach ($terms as $term) {
				if ($term->parent != 0 && in_array($term->parent, $country_terms_ids)) {
					$city_terms[] = [
						'label' => $term->name,
						'link' => str_replace('/destinations/', '/destination/', get_term_link($term)),
					];
				}
			}
			return json_encode(array_merge($region_terms, $country_terms, $city_terms));
		});

		echo $destination_json;
		die;
	}
	//ZAKARIA
	function region_result($post_body)
	{
		global $site_config;
		$taxonomy_name = 'destinations';
		$lang = $post_body["lg"];
		$region_id = $_POST['region_id'];
		$cache_destination_child_term_key = $site_config['aftg_cache']['ajax_destination_child_term']['key'] . '_' . $region_id . '_' .$lang;
		$cache_destination_child_term_time = $site_config['aftg_cache']['ajax_destination_child_term']['time'];
		$result = get_data_from_cache($cache_destination_child_term_key, 'aftg_ajax_region', $cache_destination_child_term_time, function () use ($region_id, $taxonomy_name,$lang) {
			
			global $sitepress;
			$sitepress->switch_lang($lang);

			$continents = get_terms($taxonomy_name,['parent'=>$region_id,'hide_empty'=>false]);
			$data_country = [];
			$vol_trad = __('vol à partir de : ',AFMM_TERMS);
			foreach ($continents as $continent){
				$data_city = [];
				$cities = get_terms($taxonomy_name,['parent'=>$continent->term_id,'hide_empty'=>false]);
				foreach ($cities as $city) {
					$post_linked_term = get_posts([
						'post_type'=>"destination",
						'fields'=>'ids',
						'tax_query'=>[
							[
								'taxonomy'=>'destinations',
								'field'=>'term_id',
								'terms'=>$city->term_id
							]
						]
					])[0];
					$post_meta = get_post_meta($post_linked_term);
					$price = $post_meta['lowestPrice'][0];
					$to_destination = generate_vol_url('PAR',$post_meta['origin_code'][0]);

					$data_city[] = [
						'city_name' => $city->name,
						'city_slug' => get_term_link($city,$taxonomy_name),
						'price' => $price,
						'af_url' => $to_destination,
						'vol_text' => $vol_trad,
					];
				}
				$data_country[]=[
					'country_name'=>$continent->name,
					'country_slug'=>get_term_link($continent,$taxonomy_name),
					'cities'=>$data_city,
				];
			}
			return json_encode($data_country);
		});
		echo $result;
		die;
	}
	function  build_args($array, $taxonomy, $args)
	{
		if (count($array)) {
			$args[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $array,
			);
		}

		return $args;
	}


	function get_filters_result()
	{
		$args = ['relation' => 'AND',];
		$filters_ids = json_decode(stripcslashes($_POST['filters_ids']), true);
		$regions = isset($filters_ids['region']) ? $filters_ids['region'] : [];
		$envies = isset($filters_ids['desires']) ? $filters_ids['desires'] : [];
		$mois = isset($filters_ids['departure']) ? $filters_ids['departure'] : [];
		$budget = isset($filters_ids['budget']['price-range']) ? explode(",", $filters_ids['budget']['price-range']) : [];
		$args = self::build_args($regions, "destinations", $args);
		$args = self::build_args($envies, "envies", $args);
		$args = self::build_args($mois, "mois", $args);

		$query_args = array(
			'post_type' => 'destination',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'tax_query' => [$args],
			'post_status' => 'publish'
		);

		if (count($budget) > 1) {

			$query_args['meta_query'] = array([
				'key'     => 'lowestPrice',
				'value'   => [floatval($budget[0]), floatval($budget[1])],
				'compare' => 'BETWEEN',
				'type'    => 'numeric'
			]);
		}
		$post_ids = get_posts($query_args);
		$ids = implode(",", $post_ids);
		if (count($post_ids)) {
			global $wpdb;
			$sql_filtres = (count($envies) || count($mois)) ?  " taxonomie_id in (" . implode(",", array_merge($mois, $envies)) . ") and " : '';
			$results = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT destination_id, SUM(ranking) AS Value FROM wp_ranking WHERE ranking > 0 "
						. "and " . $sql_filtres . " destination_id in (" . $ids . ") group by destination_id order by Value DESC  ",
					implode(",", array_merge($mois, $envies))
				)
			);
			if (count($results)) {
				$results=['ids'=>$results,"msg"=>__('DÉCOUVRIR LES ', AFMM_TERMS)];
				wp_send_json($results);
			} else {
				wp_send_json($results=['ids'=>$results,"msg"=>__('AUCUNE DESTINATION TROUVÉE', AFMM_TERMS)]);
			}
		}
		else {
			wp_send_json($results=['ids'=>[],"msg"=>__('AUCUNE DESTINATION TROUVÉE', AFMM_TERMS)]);
		}

		wp_reset_postdata();
		die;
	}
}

destinationsResult::register();
