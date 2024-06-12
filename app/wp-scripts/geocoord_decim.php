<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

$options = getopt('', ['language::']);
$language = !empty($options['language']) ? $options['language'] : 'fr';

$regions_file = DATA_DIR . 'regions_full_'.$language.'.json';
$countries_file = DATA_DIR . 'countries_full_'.$language.'.json';
$destinations_file = DATA_DIR . 'destinations_'.$language.'.json';
$destinations_base_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);
$destinations = Scripts_Utils::read_json_file($destinations_file);
$counter = 0;

foreach ($regions as $region) {
    foreach ($region['countries'] as $country) {
        foreach ($countries[$country]['destinations'] as $destination) {
            $dist =  Scripts_Utils::read_json_file($destinations_base_dir . $destination . '_'.$language.'.json');
            $destination_data = $dist['destination'];
            foreach ($dist["articles"] as $adresse) {
                $adresse_content = $adresse["content"];
                $poi_id = $adresse["id"];
                $adresse_post = AFTG_Helper::get_adresse_post_by_origin_id($poi_id, $language);
                if ($adresse_post) {
                    $poi_type = ($adresse["type"] == "POI") ? "adresse" : 'post';
                    if ($poi_type == "adresse") {
                        $geocoord = get_post_meta($adresse_post->ID, 'geo_coordonees', true);
                        $long_lat = explode(",", $geocoord);
                        $longitude = convert($long_lat[0]);
                        $latitude = convert($long_lat[1]);
                        if ($latitude && $longitude) {
                            $correct_geocoord = $latitude . ',' . $longitude;
                            update_post_meta($adresse_post->ID, 'geo_coordonees', $correct_geocoord);
                            println_flush($adresse_post->ID . ' bien updated');
                        }
                    }
                }
            }
        }
    }
}

println_flush($counter);

function convert($coord)
{
    $quef = -1;
    $degree = explode("°", $coord);
    $minute = explode("'", $degree[1]);
    $seconds = explode("&quot;", $minute[1]);
    $wich_char = $seconds[1];
    if ($wich_char == 'N;' || $wich_char == 'E') $quef = 1;
    if ($wich_char == null) $quef = 0;
    $result = $quef * (intval($degree[0]) + (((intval($minute[0]) * 60) + (intval($seconds[0]))) / 3600));
    return $result;
}