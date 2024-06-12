<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';
global $sitepress;
$sitepress->switch_lang( "fr" );


$regions_file = DATA_DIR . 'regions_full.json';
$countries_file = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);
$posts_ids = get_posts(array(
    'post_type' => 'destination',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'fields' => "ids"
));

foreach ($regions as $region) {

    foreach ($region['countries'] as $country) {
        foreach ($countries[$country]['destinations'] as $destination) {
            $dist =  Scripts_Utils::read_json_file($destinations_dir . $destination . '_fr.json');
            if (isset($dist['destination']['code'])) {
                $code_iata = $dist['destination']['code'];
                $dest_id = AFTG_Helper::get_dastination_post_by_iata_code($code_iata,'fr');
                if ($dest_id) {
                    $dest_id=$dest_id->ID;
                    println_flush($code_iata);
                    $prat_Infos = $dist['practicalInfos'];
                    if (isset($prat_Infos['goodToKnow']))
                        update_post_meta($dest_id, 'goodToKnow_content', json_encode($prat_Infos['goodToKnow'],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
                }
            }
        }
    }
}
