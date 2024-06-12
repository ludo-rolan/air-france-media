<?php

const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';


$regions_file     = DATA_DIR . 'regions_full.json';
$countries_file   = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

global $sitepress;
$sitepress->switch_lang( "en" );

$regions                              = Scripts_Utils::read_json_file( $regions_file );
$countries                            = Scripts_Utils::read_json_file( $countries_file );




foreach ( $regions as $region ) {

	foreach ( $region['countries'] as $country ) {
		foreach ( $countries[ $country ]['destinations'] as $destination ) {
			if($destination!="RAK")continue;
			$dist = Scripts_Utils::read_json_file( $destinations_dir . $destination . '_fr.json' );
			if ( isset( $dist['destination']['code'] ) ) {
				$code_iata = $dist['destination']['code'];
				$dest_data = AFTG_Helper::get_dastination_post_by_iata_code( $code_iata, "fr" );
				$dest_id   = $dest_data->ID ?? null;
				if ( $dest_id ) {
					println_flush( $code_iata );
					$prat_Infos = $dist['practicalInfos'];
					//TIME
					//import titles
					$keys = [
						"medical","goodToKnow","localCalendar","weather","currency","essentialPhrases",
						"touristInformation","airports","transportation"
					];


					$titles_json_gb=$dist["practicalInformationCategories"]["titles"];
					$titles_gb=[];
					foreach ($keys as $key) {
						if(isset($titles_json_gb[$key]) && isset($titles_json_gb[$key]["title"])){
							println_flush( $key);

							$titles_gb[$key]=$titles_json_gb[$key]["title"];
						}
					}
					update_is_not_single_meta_box($dest_id,$titles_gb, $keys, "titles");
				}
			}
		}
	}
}
