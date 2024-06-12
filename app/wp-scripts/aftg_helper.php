<?php

class AFTG_Helper {
    public static function get_dastination_post_by_iata_code($code,$language="fr") {
	    global $sitepress;
		$sitepress->switch_lang($language);
		$args = array(
			'post_type' => 'destination',
			'posts_per_page' => 1,
			'meta_query' => array(
				array(
					'key' => 'origin_code',
					'value' => $code,
					'compare' => '='
				)
			)
		);

		$query = new WP_Query( $args );
	    if (count($query->posts) > 1) {
		    var_dump($code);
		    var_dump($query->posts);
//			die("multiple terms found");
	    }
		return $query->posts[0] ?? null;
	}

	public static function get_adresse_post_by_origin_id($id,$language="fr") {
	    global $sitepress;
		$sitepress->switch_lang($language);
		$args = array(
			'post_type' => ['adresse','post'],
			'posts_per_page' => 1,
			'meta_query' => array(
				array(
					'key' => 'id',
					'value' => $id,
					'compare' => '='
				)
			)
		);
		$query = new WP_Query( $args );
		return $query->posts[0] ?? null;
	}

	public static function get_term_destination_by_iata_code($code,$language="fr") {
	    global $sitepress;
		$sitepress->switch_lang($language);
		// find term by termmeta code
		$args = array(
			'hide_empty' => false, // also retrieve terms which are not used yet
			'meta_query' => array(
				array(
					'key'       => 'code',
					'value'     => $code,
					'compare'   => '='
				)
			),
			'taxonomy'  => 'destinations'
		);
		$terms = get_terms( $args );
		if (is_wp_error($terms)) {
			return null;
		}
		if (count($terms) > 1) {
			var_dump($code);
			var_dump($terms);
//			die("multiple terms found");
		}
		return $terms[0] ?? null;
	}

	public static function get_term_filtre_by_code($code,$language="fr") {
		global $sitepress;
		$sitepress->switch_lang($language);
		// find term by termmeta code
		$args = array(
			'hide_empty' => false, // also retrieve terms which are not used yet
			'meta_query' => array(
				array(
					'key'       => 'code',
					'value'     => $code,
					'compare'   => '='
				)
			),
			'taxonomy'  => 'filtre'
		);
		$terms = get_terms( $args );
		if (is_wp_error($terms)) {
			return null;
		}
		if (count($terms) > 1) {
			var_dump($code);
			var_dump($terms);
		}
		return $terms[0] ?? null;
	}

	/**
	 * the function inserts the destination translation
	 * if it already exists in the default language which is "fr" in our case
	 * term model
	 * array(
		    "name" =>"Abidjan",
			"slug" => "abidjan",
			"parent" => 0,
			"metas" => array(
				"code" => "ABJ",
				"pictureUrl" => "",
				"latitude" => "",
				"longitude" => "",
				"isTravelGuideAvailable" => "",
				"travelGuideUrl" => ""
			)
		);
	 *
	 * @param $iata_code
	 * @param $term array array of data to insert for destination taxonomy
	 * @param $language
	 *
	 * @return int|null returns the id of the term is inserted or null if the origin doesn't exist
	 */
	public static function translate_taxonomy_destination_term_and_link($iata_code, $term, $language) {
		global $sitepress;
		$dl = WPML_helper::get_default_language();
		$origin = self::get_term_destination_by_iata_code($iata_code,$dl);
		$sitepress->switch_lang($language);
		if ($origin) {
			$destination = self::get_term_destination_by_iata_code($iata_code,$language);
			if ($destination) {
				$new_slug = (empty($term["slug"])) ? $destination->slug : $term["slug"];
				if (
					preg_match('~[0-9]~', $new_slug)
					||
					(
						strpos($origin->slug,$new_slug)!==false
					)
				){
					$new_slug = $origin->slug;
				}
				wp_update_term(
					$destination->term_id,
					'destinations',
					array(
						'name'=> $term["name"],
						'slug' => $new_slug,
						'parent' => $term["parent"]
					)
				);
				return $destination->term_id;
			}else{
				$new_slug = (empty($term["slug"])) ? $origin->slug : $term["slug"];
				$translated_destination_term = wp_insert_term(
					$term["name"],
					'destinations',
					array(
						'slug' => $new_slug,
						'parent' => $term["parent"]
				));
				if (is_wp_error($translated_destination_term)) {
					var_dump($translated_destination_term);
					return null;
				}
				$wpml_element_type = apply_filters( 'wpml_element_type', 'destinations' );
				$get_language_args = array(
					'element_id' => $origin->term_id,
					'element_type' => $wpml_element_type
				);
				$original_tax_destination_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
				do_action( 'wpml_set_element_language_details', [
					'element_id' => $translated_destination_term["term_id"],
					'element_type' => $wpml_element_type,
					'trid' => $original_tax_destination_language_info->trid,
					'language_code' => $language,
					'source_language_code' => $original_tax_destination_language_info->language_code,
				]);
				//update the $translated_destination_term slug with the original slug
				//get the term by id

				$new_tax = get_term_by(
					"term_id",
					$translated_destination_term["term_id"],
					"destinations"
				);
				if (!$new_tax || is_wp_error($new_tax)) {
					var_dump($translated_destination_term);
					return $translated_destination_term["term_id"];
				}
				$new_slug = (empty($term["slug"])) ? $origin->slug : $term["slug"];
				if (
					preg_match('~[0-9]~', $new_slug)
					||
					strpos($origin->slug,$new_slug)!==false
				){
					$new_slug = $origin->slug;
				}
				wp_update_term(
					$translated_destination_term["term_id"],
					'destinations',
					array(
						'name'=> $term["name"],
						'slug' => $new_slug,
						'parent' => $term["parent"]
					)
				);
				return $translated_destination_term["term_id"];
			}
		}
		return null;
	}

	public static function translate_taxonomy_filtre_term($filtre_code, $term, $language) {
		global $sitepress;
		$dl = WPML_helper::get_default_language();
		$origin = self::get_term_filtre_by_code($filtre_code,$dl);
		$sitepress->switch_lang($language);
		if ($origin) {
			$filtre = self::get_term_filtre_by_code($filtre_code,$language);
			if ($filtre) {
				$new_slug = (empty($term["slug"])) ? $filtre->slug : $term["slug"];
				if (
					preg_match('~[0-9]~', $new_slug)
					||
					(
						strpos($origin->slug,$new_slug)!==false
					)
				){
					$new_slug = $origin->slug;
				}
				wp_update_term(
					$filtre->term_id,
					'destinations',
					array(
						'name'=> $term["name"],
						'slug' => $new_slug,
						'parent' => $term["parent"]
					)
				);
				return $filtre->term_id;
			}else{
				$new_slug = (empty($term["slug"])) ? $origin->slug : $term["slug"];
				$translated_filtre_term = wp_insert_term(
					$term["name"],
					'filtre',
					array(
						'slug' => $new_slug,
						'parent' => $term["parent"]
					));
				if (is_wp_error($translated_filtre_term)) {
					var_dump($translated_filtre_term);
					return null;
				}
				$wpml_element_type = apply_filters( 'wpml_element_type', 'filtre' );
				$get_language_args = array(
					'element_id' => $origin->term_id,
					'element_type' => $wpml_element_type
				);
				$original_tax_destination_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
				do_action( 'wpml_set_element_language_details', [
					'element_id' => $translated_filtre_term["term_id"],
					'element_type' => $wpml_element_type,
					'trid' => $original_tax_destination_language_info->trid,
					'language_code' => $language,
					'source_language_code' => $original_tax_destination_language_info->language_code,
				]);
				//update the $translated_filtre_term slug with the original slug
				//get the term by id

				$new_tax = get_term_by(
					"term_id",
					$translated_filtre_term["term_id"],
					"filtre"
				);
				if (!$new_tax || is_wp_error($new_tax)) {
					var_dump($translated_filtre_term);
					return $translated_filtre_term["term_id"];
				}
				$new_slug = (empty($term["slug"])) ? $origin->slug : $term["slug"];
				if (
					preg_match('~[0-9]~', $new_slug)
					||
					strpos($origin->slug,$new_slug)!==false
				){
					$new_slug = $origin->slug;
				}
				wp_update_term(
					$translated_filtre_term["term_id"],
					'filtre',
					array(
						'name'=> $term["name"],
						'slug' => $new_slug,
						'parent' => $term["parent"]
					)
				);
				return $translated_filtre_term["term_id"];
			}
		}
		return null;
	}


	public static function translate_posttype_destination($iata_code, $post, $language){
		global $sitepress;
		$post_data = $post["post"];
		$post_metas = $post["metas"];

		$dl = WPML_helper::get_default_language();
		$origin = self::get_dastination_post_by_iata_code($iata_code,$dl);
		//get post thumbnail ID
		$thumbnail_id = get_post_thumbnail_id($origin);
		$sitepress->switch_lang($language);
		if ($origin) {
			$destination = self::get_dastination_post_by_iata_code($iata_code,$language);
			if ($destination) {
				$post_data['ID'] = $destination->ID;
				if (is_wp_error(wp_update_post($post_data))){
					echo "Error updating Destination Post : $destination->post_name \n";
					return null;
				}else{
					//update all post metas
					foreach ($post_metas as $meta_key => $meta_val ){
						update_post_meta($destination->ID, $meta_key,$meta_val);
						echo "Updated the meta $meta_key .. for $destination->post_name";
					}
					return $destination->ID;
				}
			}else{
				$translated_destination_post = wp_insert_post($post_data);
				if (is_wp_error($translated_destination_post) || $translated_destination_post == 0) {
					echo "Error inserting Destination : $post_data \n";
					return null;
				}
				$wpml_element_type = apply_filters( 'wpml_element_type', 'destination' );
				$get_language_args = array(
					'element_id' => $origin->ID,
					'element_type' => $wpml_element_type
				);
				$original_tax_destination_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
				do_action( 'wpml_set_element_language_details', [
					'element_id' => $translated_destination_post,
					'element_type' => $wpml_element_type,
					'trid' => $original_tax_destination_language_info->trid,
					'language_code' => $language,
					'source_language_code' => $original_tax_destination_language_info->language_code,
				]);
				$new_post =  get_post($translated_destination_post);
				if (!$new_post || is_wp_error($new_post)) {
					return $translated_destination_post;
				}
				$post_data["ID"] = $translated_destination_post;
				if (is_wp_error(wp_update_post($post_data))){
					echo "Error updating Destination Post slug: $post_data \n";
					return $translated_destination_post;
				}
				//update all post metas
				foreach ($post_metas as $meta_key => $meta_val ){
					update_post_meta($translated_destination_post, $meta_key,$meta_val);
					$post_slug = $post_data["post_name"];
					echo "Updated the meta $meta_key .. for $post_slug ";
				}
				set_post_thumbnail($translated_destination_post,$thumbnail_id);
				return $translated_destination_post;
			}
		}
		return null;
	}

	public static function translate_posttype_adresse($poi_id, $post, $language){
		global $sitepress;
		$post_data = $post["post"];
		$post_metas = $post["metas"];
		$dl = WPML_helper::get_default_language();
		$origin = self::get_adresse_post_by_origin_id($poi_id,$dl);
		$thumbnail_id = get_post_thumbnail_id($origin);
		$sitepress->switch_lang($language);
		if ($origin) {
			$adresse = self::get_adresse_post_by_origin_id($poi_id,$language);
			if ($adresse) {
				$post_data['ID'] = $adresse->ID;
				if (is_wp_error(wp_update_post($post_data))){
					echo "Error updating Adresse Post : $adresse->post_name \n";
					return null;
				}else{
					//update all post metas
					foreach ($post_metas as $meta_key => $meta_val ){
						update_post_meta($adresse->ID, $meta_key,$meta_val);
						echo "Updated the meta $meta_key .. for $adresse->post_name";
					}
					return $adresse->ID;
				}
			}else{
				$translated_adresse_post = wp_insert_post($post_data);
				if (is_wp_error($translated_adresse_post) || $translated_adresse_post == 0) {
					echo "Error inserting Destination : $post_data \n";
					return null;
				}
				$wpml_element_type = apply_filters( 'wpml_element_type', $post["type"] );
				$get_language_args = array(
					'element_id' => $origin->ID,
					'element_type' => $wpml_element_type
				);
				$original_tax_destination_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
				do_action( 'wpml_set_element_language_details', [
					'element_id' => $translated_adresse_post,
					'element_type' => $wpml_element_type,
					'trid' => $original_tax_destination_language_info->trid,
					'language_code' => $language,
					'source_language_code' => $original_tax_destination_language_info->language_code,
				]);
				$new_post =  get_post($translated_adresse_post);
				if (!$new_post || is_wp_error($new_post)) {
					return $translated_adresse_post;
				}
				$post_data["ID"] = $translated_adresse_post;
				if (is_wp_error(wp_update_post($post_data))){
					echo "Error updating Adresse Post slug: $post_data \n";
					return $translated_adresse_post;
				}
				//update all post metas
				foreach ($post_metas as $meta_key => $meta_val ){
					update_post_meta($translated_adresse_post, $meta_key,$meta_val);
					$post_slug = $post_data["post_name"];
					echo "Updated the meta $meta_key .. for $post_slug ";
				}
				set_post_thumbnail($translated_adresse_post,$thumbnail_id);
				return $translated_adresse_post;
			}
		}
		return null;
	}
	public static function get_dastination_by_country($country_slug) {
		$args = array(
			'post_type' => 'destination',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'destinations',
					'field'    => 'slug',
					'terms'    => $country_slug
				)
			)
		);
		return get_posts($args);
	}


}