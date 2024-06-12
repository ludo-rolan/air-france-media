<?php

const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';
//Durant le script il faut changer la langue en anglais _fr.json et "fr"

$regions_file     = DATA_DIR . 'regions_full.json';
$countries_file   = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

global $sitepress;
$sitepress->switch_lang( "fr" );

$regions = Scripts_Utils::read_json_file( $regions_file );
$countries= Scripts_Utils::read_json_file( $countries_file );



foreach ( $regions as $region ) {

	foreach ( $region['countries'] as $country ) {
		foreach ( $countries[ $country ]['destinations'] as $destination ) {
			$dist = Scripts_Utils::read_json_file( $destinations_dir . $destination . '_fr.json' );
			if ( isset( $dist['destination']['code'] ) ) {
				$code_iata = $dist['destination']['code'];
				$dest_data = AFTG_Helper::get_dastination_post_by_iata_code( $code_iata, "fr" );
				$dest_id   = $dest_data->ID ?? null;
				if ( $dest_id ) {
					println_flush( $code_iata );
					if(isset($dist['destination']['content']['seoData']['seoKeywords'])){
						update_post_meta($dest_id,'rank_math_focus_keyword',$dist['destination']['content']['seoData']['seoKeywords']);
					}
				}
			}
		}
	}
}
