<?php

const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';
//Durant le script il faut changer la langue en anglais _fr.json et "fr"

$regions_file     = DATA_DIR . 'regions_full.json';
$countries_file   = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';


$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);
$list_transportation = Scripts_Utils::read_json_file(DATA_DIR . '/practical_info/list_transportations.json');

$arrays = [];
$lgs = ['fr', 'en'];
foreach ($lgs as $lg) {
    global $sitepress;
    $sitepress->switch_lang($lg);
    foreach ($regions as $region) {

        foreach ($region['countries'] as $country) {
            foreach ($countries[$country]['destinations'] as $destination) {
                $dist = Scripts_Utils::read_json_file($destinations_dir . $destination . '_' . $lg . '.json');
                if (isset($dist['destination']['code'])) {

                    $code_iata = $dist['destination']['code'];
                    $dest_data = AFTG_Helper::get_dastination_post_by_iata_code($code_iata, $lg);
                    $dest_id   = $dest_data->ID ?? null;
                    if ($dest_id) {
                        println_flush($code_iata);
                        $old_transport = get_post_meta($dest_id, 'transportation', true);

                        $transportation_value = "";
                        if (isset($list_transportation[$code_iata][$lg])) {
                            foreach ($list_transportation[$code_iata][$lg] as $tranport) {
                                if (isset($tranport['label']))
                                    $transportation_value .= "<h4>" . $tranport['label'] . "</h4>";
                                if (isset($tranport['description'])) {
                                    $transportation_value .= $tranport['description'];
                                }
                            }
                        }
                        if (!is_array($old_transport)) {
                            $old_transport = [];
                        }
                        if (isset($old_transport["transportation_description"])) {
                            $old_transport["transportation_description"] .= $transportation_value;
                        } else {
                            $arrays[] = $code_iata;
                            $old_transport["transportation_description"] = $transportation_value;
                        }
                        update_post_meta($dest_id, 'transportation', $old_transport);
                    }
                }
            }
        }
    }
}
