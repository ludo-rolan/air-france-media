<?php

function get_adresse_slug($adresse) {
	if ($adresse["articleUrl"]){
		$slug  = str_replace(
			'https://www.airfrance.fr/FR/fr/common/travel-guide/',
			'',
			$adresse["articleUrl"]
		);
		return str_replace(
			'.htm',
			'',
			$slug
		);
	}
	else {
		return Scripts_Utils::slugify( $adresse["content"]["title"] );
	}
}

// function to create a "filtre" taxonomy term  if doen't exist by name and return the term id
function add_filtre_term($filtre) {
	$filtre_name = $filtre["label"];
	$filtre_code = $filtre["code"];
	$term = get_term_by('name', $filtre_name, 'filtre');
	// get term id
	$term_id = ($term)?$term->term_id:null;
	if (!$term) {
		$term = wp_insert_term($filtre_name, 'filtre');
		if ( is_wp_error( $term ) ) {
			echo "Error creating Filtre : $filtre_name\n";
		}
		else{
			$term_id = $term["term_id"];
			echo "Created Filtre: $filtre_name\n";
		}

	}
	//update meta term "code"
	update_term_meta( $term_id, "code", $filtre_code );
	return $term_id;
}

// update address post metas
function update_address_post_metas($address,$post_id) {
	$metas = array(
		"type",
		"id",
		"articleUrl",
		"city",
		"country",
		"region",
		"topic",
		"publicationDate",
		"isHidden",
		"isPartner",
		"isCrush",
		"isMultiAddress",
	);
	foreach ($metas as $meta) {
		if (isset($address[$meta])) {
			update_post_meta($post_id, $meta, $address[$meta]);
		}
	}
}


function add_distination_adresse ($adresse,$destination_term_id){
	$adresse_slug = get_adresse_slug($adresse);
	$posts = get_posts(array(
		'name' => $adresse_slug,
		'post_type' => ['adresse','post'],
		'post_status' => 'publish',
		'posts_per_page' => 1
	));
	$post_id = ($posts)?$posts[0]->ID:null;
	$post_exerpt = $adresse["content"]["introduction"]["small"];
	$post_content = "";
	$geo_cordo = "";
	$adresse['isMultiAddress'] = false;
	// append all $adresse["content"]["paragraphs"] "text" to $post_content
	foreach ($adresse["content"]["paragraphs"] as $paragraph) {
		$post_content .= $paragraph["text"];
		if(isset($paragraph["picture"]) && !empty($paragraph["picture"]["url"])){
			$url = $paragraph["picture"]["url"];
			$attachment_id = Media_Helper::download_attachment_by_url($url);
			$attach_url =  Media_Helper::get_attachment_url($attachment_id);
			$post_content .= "<img src='".$attach_url."' />";
		}
		if (isset($paragraph["moreInfo"])) {
			if(count($paragraph["moreInfo"])>1){
				$adresse['isMultiAddress'] = true;
			}
			foreach ($paragraph["moreInfo"] as $moreInfo) {
				$geo_cordo .= $moreInfo["geoCoordinates"]['longitude']
				              . ","
				              . $moreInfo["geoCoordinates"]['latitude'] . ";"
				;
			}
		}
	}
	$post_type = ($adresse["type"]=="POI")?"adresse":"post";
	$post = array(
		'post_title' => $adresse["content"]["title"],
		'post_name' => $adresse_slug,
		'post_status' => 'publish',
		'post_content' => $post_content,
		'post_exerpt' => $post_exerpt,
		'post_type' => ($adresse["type"]=="POI")?"adresse":"post",
		'post_date' => $adresse["publicationDate"],
	);
	$filtres = ($post_type == "adresse")?$adresse["filters"]:[];
	if (!$post_id) {
		$post_id = wp_insert_post($post);
		if ( is_wp_error( $post_id ) ) {
			echo "Error creating Adresse Post : $adresse_slug\n";
		}
		else{
			// add destination term to post
			echo "Created Adresse Post: $adresse_slug\n";
		}
	}
	else{
		$post['ID'] = $post_id;
		if (is_wp_error(wp_update_post($post))) {
			echo "Error updating Adresse Post : $adresse_slug\n";
		}
		else{
			//get post id from slug
			echo "Updated Adresse Post: $adresse_slug\n";
		}
	}
	if ($post_id){
		update_address_post_metas($adresse,$post_id);
		setup_destination_data( $post_type, $post_id, $geo_cordo, $filtres, $adresse["category"]??null );
		wp_set_object_terms( $post_id, $destination_term_id, 'destinations', true );

		Post_Helper::set_post_thumbnail_by_url(
			$post_id,
			$adresse["content"]["mainPicture"]["url"],
			$adresse["content"]["mainPicture"]["accessibilityDescription"]??"",
			$adresse["publicationDate"]??""
		);
	}
	// update all filtres
	return [
		"post_id"=> $post_id,
		"destination_id"=> $destination_term_id
	];
}

/**
 * @param string $post_type
 * @param int $post_id
 * @param string $geo_cordo
 * @param mixed $filtres
 * @param $category1
 *
 * @return void
 */
function setup_destination_data(  $post_type,  $post_id,  $geo_cordo,  $filtres, $category1 ){
	if ( $post_type == "adresse" ) {
		update_post_meta( $post_id, "geo_coordonees", htmlspecialchars($geo_cordo) );
		$filters_ids = [];
		foreach ( $filtres as $filtre ) {
			$filters_ids[] = add_filtre_term( $filtre );
		}
		wp_set_object_terms( $post_id, $filters_ids, 'filtre', true );
	} else {
		// add category if not exist and add term to post
		$category_name = $category1["label"];
		$category      = get_term_by( 'name', $category_name, 'category' );
		$category_id   = ( $category ) ? $category->term_id : null;
		if ( ! $category ) {
			$category = wp_insert_term( $category_name, 'category' );
			if ( is_wp_error( $category ) ) {
				echo "Error creating Category : $category_name\n";
			} else {
				update_term_meta( $category["term_id"], "code", $category1["code"] );
				$category_id = $category["term_id"];
				echo "Created Category: $category_name\n";
			}
		}
		wp_set_object_terms( $post_id, $category_id, 'category' );
	}
}


