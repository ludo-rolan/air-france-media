<?php

const PROJECT_NAME = 'aftg';
require '../init.php';
require '../helper.php';
require '../aftg_helper.php';

$regions_file = DATA_DIR . 'regions_full.json';
$countries_file = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);

$options = getopt('', ['language::']);
$language = !empty($options['language']) ? $options['language'] : 'fr';

global $sitepress;
$sitepress->switch_lang($language);

$destination_timezone_file = [];
foreach ($regions as $region) {
	foreach ( $region['countries'] as $country ) {
		foreach ( $countries[$country]['destinations'] as $destination ) {
			$dist =  Scripts_Utils::read_json_file($destinations_dir . $destination . '_' . $language . '.json');
			$destination_lat = $dist['destination']['latitude'];
			$destination_lng = $dist['destination']['longitude'];
			$post = get_page_by_path($dist['destination']['label'], OBJECT, 'destination');
			$post_id = $post->ID;

			if ($language == 'en') {
				$post_id = apply_filters( 'wpml_object_id', $post->ID, 'destination', false, $language );
			}

			$url = "https://api.timezonedb.com/v2.1/get-time-zone?key=G09AGYMJ016D&format=json&by=position&lat=$destination_lat&lng=$destination_lng";
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = json_decode(curl_exec($ch));
			if (!empty($post_id) && $response->status == "OK") {
				add_post_meta($post_id, 'country_time_zone_name', $response->zoneName);
				print_flush($dist['destination']['label'] . " time zone name was retrieved \n");
			}
			sleep(1);
		}
	}
}