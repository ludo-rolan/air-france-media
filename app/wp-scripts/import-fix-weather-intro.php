<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';
global $sitepress;
$sitepress->switch_lang("fr");


$regions_file = DATA_DIR . 'regions_full.json';
$countries_file = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);

$titles['DestinationWeather'] = [
    'title', 'introduction',
    'weatherUnit', 'temperatureValue',
    'pictogramUrlSvg', 'pictogramUrl'
];


foreach ($regions as $region) {

    foreach ($region['countries'] as $country) {
        foreach ($countries[$country]['destinations'] as $destination) {
            $dist =  Scripts_Utils::read_json_file($destinations_dir . $destination . '_en.json');
            if (isset($dist['destination']['code'])) {
                $code_iata = $dist['destination']['code'];
                $dest_id = AFTG_Helper::get_dastination_post_by_iata_code($code_iata, 'en');
                if ($dest_id) {
                    $dest_id = $dest_id->ID;
                    println_flush($code_iata);
                    $prat_Infos = $dist['practicalInfos'];
                    //weather
                    $weather = [];
                    isset($prat_Infos['weather']['content']['title']) ? $weather["title"] = $prat_Infos['weather']['content']['title'] : '';
                    isset($prat_Infos['weather']['content']['introduction']) ? $weather["introduction"] = $prat_Infos['weather']['content']['introduction'] : 'x';
                    isset($prat_Infos['weather']['temperature']) ? $temperature = $prat_Infos['weather']['temperature'][0] : '';
                    $weather["temperatureValue"] = $temperature['value'] . ',' . $temperature['unit'];
                    isset($prat_Infos['weather']['pictogramUrl']) ? $weather["pictogramUrl"] = $prat_Infos['weather']['pictogramUrl'] : '';

                    update_is_not_single_meta_box($dest_id, $weather, $titles['DestinationWeather'], "DestinationWeather");

                }
            }
        }
    }
}
