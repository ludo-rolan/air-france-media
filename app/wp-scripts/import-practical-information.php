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
$titles['DestinationWeather'] = [
    'title', 'introduction',
    'weatherUnit', 'temperatureValue',
    'pictogramUrlSvg', 'pictogramUrl'
];
$titles['destinationSpokenLanguages'] = ['code', 'label'];
$titles['titles'] = [
    'medical', 'goodToKnow', 'weather', 'currency', 'essentialPhrases',
    'touristInformation', 'airports', 'transportation'
];
$titles['currency'] = ['label', 'symbol', 'rate', 'title', 'description', 'validity'];
$titles['time'] = ['jetLag', 'timeZone', 'effectiveFlightDuration'];
$titles['localCalendar'] = ['title', 'description'];
$titles['airports'] = ['title', 'description'];
$titles['transportation'] = ['title', 'description'];
$titles['touristInformation'] = ['title', 'description'];
$titles['medical'] = ['title', 'description'];
$titles['administrativeProcedures'] = ['title', 'description'];
$titles['usefulAddresses'] = ['title', 'description'];

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
                    //TIME
                    isset($prat_Infos['time']) ? $time = $prat_Infos['time'] : '';
                    isset($prat_Infos['effectiveFlightDuration']) ? $time['effectiveFlightDuration'] = $prat_Infos['effectiveFlightDuration'] : '';
                    update_is_not_single_meta_box($dest_id, $time, $titles['time'], "time");
                    //currency 
                    if (isset($prat_Infos['currency'])) {
                        $currency = array_merge($prat_Infos['currency']['data'], $prat_Infos['currency']['content']);
                        update_is_not_single_meta_box($dest_id, $currency, $titles['currency'], "currency");
                    }
                    //airports
                    if (isset($prat_Infos['airports'])) {
                        update_is_not_single_meta_box($dest_id, $prat_Infos['airports'], $titles['airports'], "airports");
                    }
                    //transportation
                    if (isset($prat_Infos['transportation'])) {
                        update_is_not_single_meta_box($dest_id, $prat_Infos['transportation'], $titles['transportation'], "transportation");
                    }
                    //localCalendar
                    if (isset($prat_Infos['localCalendar'])) {
                        $local_cal = $prat_Infos['localCalendar'];
                        if (isset($local_cal['events'])) {
                            foreach ($local_cal['events'] as $event) {
                                if (isset($event['title'])) {

                                    $local_cal['description'] .= '<h4>' . strtoupper($event['title']) . '</h4>';
                                }
                                if (isset($event['description'])) {
                                    $local_cal['description'] .= $event['description'] . '<br>';
                                }
                            }
                        }
                    }
                    update_is_not_single_meta_box($dest_id, $local_cal, $titles['localCalendar'], "localCalendar");
                    //weather
                    $weather = [];
                    isset($prat_Infos['weather']['content']['title']) ? $weather["title"] = $prat_Infos['weather']['content']['title'] : '';
                    isset($prat_Infos['weather']['content']['content']) ? $weather["introduction"] = $prat_Infos['weather']['content']['introduction'] : '';
                    isset($prat_Infos['weather']['temperature']) ? $temperature = $prat_Infos['weather']['temperature'][0] : '';
                    $weather["temperatureValue"] = $temperature['value'] . ',' . $temperature['unit'];
                    isset($prat_Infos['weather']['pictogramUrl']) ? $weather["pictogramUrl"] = $prat_Infos['weather']['pictogramUrl'] : '';
                    update_is_not_single_meta_box($dest_id, $weather, $titles['DestinationWeather'], "DestinationWeather");
                    //tourist information & medical
                    $practical_parts = ['touristInformation', 'medical'];
                    foreach ($practical_parts  as $part) {
                        $values=[];
                        if (isset($prat_Infos[$part]['information'])) {
                            $values = $prat_Infos[$part];
                            
                            foreach ($values['information'] as $info) {
                                if (isset($info['title'])) {

                                    $values['description'] .= '<h4>' . strtoupper($info['title']) . '</h4>';
                                }
                                if (isset($info['description'])) {
                                    $values['description'] .= $info['description'] . '<br>';
                                }
                            }
                        }
                        update_is_not_single_meta_box($dest_id, $values, $titles[$part], $part);
                    }
                    //administrativeProcedures
                    if (isset($prat_Infos["administrativeProcedures"][0])) {
                        $administrativeProcedures = $prat_Infos["administrativeProcedures"][0];
                        update_is_not_single_meta_box($dest_id, $administrativeProcedures, $titles['administrativeProcedures'], "administrativeProcedures");
                    }
                    if (isset($prat_Infos['usefulAddresses'])) {
                        $usefulAddresses = $prat_Infos['usefulAddresses'];
                        $description = '';
                        foreach ($usefulAddresses as $adr) {
                            if (isset($adr['title'])) {

                                $description .= '<h4>' . $adr['title'] . '</h4>';
                            }
                            if (isset($adr['description'])) {
                                $description .= $adr['description'] . '<br>';
                            }
                        }

                        update_is_not_single_meta_box($dest_id, ["description" => $description], $titles['usefulAddresses'], "usefulAddresses");
                    }
                    $code_editor_meta = ['goodToKnow', 'monthInformation'];
                    if (isset($prat_Infos['goodToKnow']))
                        update_post_meta($dest_id, 'goodToKnow_content', json_encode($prat_Infos['goodToKnow']));
                    if (isset($prat_Infos['weather']['content']['monthInformation'])) {
                        $monthInformation = $prat_Infos['weather']['content']['monthInformation'];
                        update_post_meta($dest_id, 'monthInformation_content', json_encode($monthInformation));
                    }
                }
            }
        }
    }
}
