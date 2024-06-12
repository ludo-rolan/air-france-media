<?php

const PROJECT_NAME = 'aftg';

require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

ini_set('memory_limit', '2024M');
ini_set('max_execution_time', '900');
set_time_limit(900);

function get_data_by_lang($lang)
{
    $regions_file = DATA_DIR . 'regions_full.json';
    $countries_file = DATA_DIR . 'countries_full.json';
    $destinations_base_dir = DATA_DIR . 'destinations/';

    $regions = Scripts_Utils::read_json_file($regions_file);
    $countries = Scripts_Utils::read_json_file($countries_file);

    println_flush('------------ GET DATA BY LANG : ' . $lang . ' -------------');

    $data_fr = [];
    foreach ($regions as $region) {
        foreach ($region['countries'] as $country) {
            foreach ($countries[$country]['destinations'] as $destination) {
                $dist =  Scripts_Utils::read_json_file($destinations_base_dir . $destination . '_' . $lang . '.json');
                foreach ($dist["articles"] as $adresse) {
                    $tpk = str_replace(' – ', ' - ', $adresse['topic']);
                    if (strpos($tpk, ' - ') !== false) {
                        $exploded = explode(' - ', $tpk);
                        $data_fr[$adresse['id']] = $exploded;
                        println_flush('catching exploded data for :' . $lang . ' => '.$tpk. ' '.  $adresse['id']);
                    } else {
                        $data_fr[$adresse['id']] = [$tpk];
                        println_flush('catching exploded data for :' . $lang . ' => '.$tpk. ' '. $adresse['id']);
                    }
                }
            }
        }
    }
    return $data_fr;
}


function push_to_wp($data, $lang)
{

    print_flush('------------ PUSH TO WP : ' . $lang . ' -------------');
    global $sitepress;
    $sitepress->switch_lang($lang);

    foreach ($data as $key => $value) {
        $adresse_post = AFTG_Helper::get_adresse_post_by_origin_id($key, $lang);
        if ($value !== null) {
            foreach ($value as $term_fr) {
                $term_exist = term_exists($term_fr, 'post_tag');
                if ($term_exist === null && is_string($term_fr)) {
                    $term = wp_insert_term($term_fr, 'post_tag');
                    if (!is_wp_error($term)) {
                        wp_set_object_terms($adresse_post->ID, $term['term_id'], 'post_tag', true);
                        println_flush('term added for :' . $adresse_post->ID);
                    }
                } elseif ($term_exist !== null && is_string($term_fr)) {
                    $term_id = intval($term_exist['term_id']);
                    wp_set_object_terms($adresse_post->ID, $term_id, 'post_tag', true);
                    println_flush('term updated for :' . $adresse_post->ID);
                }
            }
        }
    }
}


$data_en = get_data_by_lang('en');
$data_fr = get_data_by_lang('fr');


push_to_wp($data_fr, 'fr');
push_to_wp($data_en, 'en');
