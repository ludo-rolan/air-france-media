<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'aftg_helper.php';
$regions_file = DATA_DIR . 'regions_full_en.json';
$countries_file = DATA_DIR . 'countries_full_en.json';
$destinations_file = DATA_DIR . 'destinations_en.json';
$destinations_base_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);
$destinations = Scripts_Utils::read_json_file($destinations_file);



$filtres_fr = [
	"MUST_SEE" =>"A Découvrir",
	"ACCOMODATION" =>"Hébergements",
	"GOURMET_ADVENTURES" =>"Balades Gourmandes",
	"OUTTINGS" =>"Sorties",
	"ARTS_AND_CULTURE" =>"Arts et Culture",
	"SPORTS_AND_WELL_BEING" =>"Sport et Bien-Etre",
];

$filtres_en = [
	"MUST_SEE" =>"Must See",
	"ACCOMODATION" =>"Accommodation",
	"GOURMET_ADVENTURES" =>"Food and Drink",
	"OUTTINGS" =>"Going out",
	"ARTS_AND_CULTURE" =>"Art and Culture",
	"SPORTS_AND_WELL_BEING" =>"Sports and Well-being",
];
$wp_filter_ids_en = [];


foreach ($filtres_en as $key => $value) {
	$filtre = array(
		"name" =>$value,
		"slug" => Scripts_Utils::slugify($value),
		"parent" => 0,
		"metas" => array(
			"code" => $key,
		)
	);
	$translated_id = AFTG_Helper::translate_taxonomy_filtre_term($key, $filtre, "en");
	$wp_filter_ids_en[$key] =  $translated_id;
}




foreach ($regions as $region) {
	foreach ( $region['countries'] as $country ) {
		foreach ( $countries[$country]['destinations'] as $destination ) {
			$dist =  Scripts_Utils::read_json_file($destinations_base_dir . $destination . '_en.json');
			$destination_data = $dist['destination'];
			foreach ($dist["articles"] as $adresse) {
				$adresse_content = $adresse["content"];
				$poi_id = $adresse["id"];
				$adresse_post = AFTG_Helper::get_adresse_post_by_origin_id($poi_id, "en");
				if ($adresse_post ){
					$poi_filtres = $adresse["filters"];
					$poi_type = ($adresse["type"] == "POI")?"adresse":'post';
					$adresse_filtres = [];
					if ($poi_type == "adresse") {
						foreach ($poi_filtres as  $fil) {
							$adresse_filtres[] = $wp_filter_ids_en[$fil['code']];
						}
						wp_set_post_terms( $adresse_post->ID, $adresse_filtres, 'filtre', true );
					}
					$destination_term = AFTG_Helper::get_term_destination_by_iata_code($destination, "en");
					if ($destination_term) {
						wp_set_post_terms( $adresse_post->ID, $destination_term->term_id, 'destinations', true );
					}
				}
			}
		}
	}
}

