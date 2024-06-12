<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'import-meta-destination.php';
require 'import-adresse.php';

$regions_file = DATA_DIR . 'regions_full.json';
$countries_file = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);

function get_destination_slug($destination)
{
	// changed in regions_full.json from FRA to FRANCE
	// because we have the same airport code for Frankfort Germany
    if ($destination["code"] == "FRANCE") {
        return "france-zone";
    }
	// both the country and the region have the same code in the data
	// so we need to change the slug for the country
	if ($destination["code"] == "MU") {
		return "ilemaurice";
	}
	if ($destination["code"] == "HK") {
		return "hong-kong-china";
	}
	elseif ($destination["code"] == "PA") {
		return "panama-america";
	}
	elseif ($destination["code"] == "SG") {
		return "singapour-asia";
	}
	elseif ($destination["code"] == "SX") {
		$term_model["slug"] = "saint-martin-sx";
	}
    return ($destination["travelGuide"]["isTravelGuideAvailable"])
        ? str_replace(
            'https://www.airfrance.fr/guide-voyage/',
            '',
            $destination["travelGuide"]["travelGuideUrl"]
        )
        : Scripts_Utils::slugify($destination["label"]);
}


//function to add a "destinations" taxonomy term if it doesn't exist and return the term object
$destination_term_modal = array(
	"name" =>"",
	"slug" => "",
	"parent" => "",
	"metas" => array(
		"code" => "",
		"pictureUrl" => "",
		"latitude" => "",
		"longitude" => "",
		"isTravelGuideAvailable" => "",
		"travelGuideUrl" => ""
	)
);
function add_destination_term($destination, $destination_parent_id = 0)
{
    $destination_name = $destination["label"];
    $destination_slug = get_destination_slug($destination);
    $term = get_term_by('slug', $destination_slug, 'destinations');
    // get term id
    $term_id = ($term) ? $term->term_id : null;
    if (!$term) {
        $term = wp_insert_term($destination_name, 'destinations', array(
			'slug' => $destination_slug,
            'parent' => $destination_parent_id
        ));
        if (is_wp_error($term)) {
			var_dump($term);
            echo "Error creating Destination : $destination_name\n <br>";
        }else {
			$term_id = $term['term_id'];
			echo "Created Destination: $destination_name\n <br>";
		}
    }
    // update term if already exist
    else {
        wp_update_term(
            $term_id,
            'destinations',
            array(
                'name' => $destination_name,
	            "slug" => $destination_slug,
				'parent' => $destination_parent_id
            )
        );
        echo "Updated Destination: $destination_name\n <br>";
    }
    $metas = array(
        "code",
        "pictureUrl",
        "latitude",
        "longitude",
    );
    foreach ($metas as $meta) {
        update_term_meta($term_id, $meta, $destination[$meta]);
    }
	$attachment_id = Media_Helper::download_attachment_by_url( $destination["pictureUrl"]);
	update_term_meta($term_id, "destinations-image-id", $attachment_id);
	update_term_meta($term_id, "isTravelGuideAvailable", $destination["travelGuide"]["isTravelGuideAvailable"]);
    update_term_meta($term_id, "travelGuideUrl", $destination["travelGuide"]["travelGuideUrl"]);
    return $term_id;
}


function add_destination_post($destination,$destination_parent_id = 0) {
	$destination_name = $destination["destination"]["label"];
	$destination_slug = get_destination_slug($destination["destination"]);
	$destination_id = add_destination_term($destination["destination"],$destination_parent_id);
	$posts = get_posts(array(
		'name' => $destination_slug,
		'post_type' => 'destination',
		'post_status' => 'publish',
		'posts_per_page' => 1
	));
	$post_id = ($posts)?$posts[0]->ID:null;

    $post = array(
        'post_title' => $destination["destination"]["label"],
        'post_name' => $destination_slug,
        'post_status' => 'publish',
        'post_content' => $destination["destination"]["content"]["intro"],
        'post_type' => 'destination',
    );
    if (!$post_id) {
        $post_id = wp_insert_post($post);
        if (is_wp_error($post_id)) {
            echo "Error creating Destination Post : $destination_name\n <br>";
        } else {
            echo "Created Destination Post: $destination_name\n <br>";
        }
    } else {
		$post['ID'] = $post_id;
        if (is_wp_error(wp_update_post($post))) {
            echo "Error updating Destination Post : $destination_name\n";
        } else {
            echo "Updated Destination Post: $destination_name\n <br>";
	        wp_set_object_terms( $post_id, $destination_id, 'destinations' );
        }
    }
    if ($post_id>0) {
        lunch_script_destination_meta($destination,$post_id);
	    Post_Helper::set_post_thumbnail_by_url(
		    $post_id,
		    $destination["destination"]["pictureUrl"],
		    $destination["destination"]["pictureAccessibility"],
	    );
    }
    else {
        echo "Meta Destination not saved" .$destination .''.$post_id."\n <br>";
    }
	return [
        "post_id"=> $post_id,
        "destination_id"=> $destination_id
    ];
}


foreach ($regions as $region) {
	
	$region_term_id = add_destination_term($region);
	foreach ( $region['countries'] as $country ) {
		$country_term_id = add_destination_term($countries[$country],$region_term_id);
		foreach ( $countries[$country]['destinations'] as $destination ) {
			$dist =  Scripts_Utils::read_json_file($destinations_dir . $destination . '_fr.json');
			$ids = add_destination_post($dist,$country_term_id);
			$destination_term_id = $ids["destination_id"];
			$post_id = $ids["post_id"];
			foreach ($dist["articles"] as $adresse) {
				add_distination_adresse($adresse,$destination_term_id);
			}
		}
	}
}
