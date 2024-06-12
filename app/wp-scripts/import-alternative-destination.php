<?php
const PROJECT_NAME = 'aftg';
require 'init.php';



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
$destinations = [];
foreach ($posts_ids as $post_id) {

    $meta =  get_post_meta($post_id, 'origin_code', true);
    if ($meta) {
        $destinations[$meta] = $post_id;
    }
}

foreach ($regions as $region) {

    foreach ($region['countries'] as $country) {
        foreach ($countries[$country]['destinations'] as $destination) {
            $dist =  Scripts_Utils::read_json_file($destinations_dir . $destination . '_fr.json');
            if (isset($dist['destination']['code'])) {
                $code_iata = $dist['destination']['code'];
                $alternatives = $dist['alternateDestinations'];
                $alts_ids = [];
                if (isset($destinations[$code_iata])) {

                    foreach ($alternatives as $alt) {
                        if (isset($alt['code'])  && isset($destinations[$alt['code']])) {
                            $alts_ids[] = $destinations[$alt['code']];
                        }
                    }
                    echo update_post_meta($destinations[$code_iata],'alternateDestinations_ids',$alts_ids);
                }
            }
            
        }
    }
}
