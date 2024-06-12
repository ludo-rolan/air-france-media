<?php

const PROJECT_NAME = 'aftg';
require 'init.php';
require 'import-meta-destination.php';
require 'import-adresse.php';
require 'aftg_helper.php';
ini_set('memory_limit', '2024M');
ini_set('max_execution_time', '300');
set_time_limit(300);
$regions_file = DATA_DIR . 'regions_full.json';
$countries_file = DATA_DIR . 'countries_full.json';
$destinations_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);

foreach ($regions as $region) {

    foreach ($region['countries'] as $country) {
            foreach ($countries[$country]['destinations'] as $destination) {
            $dist =  Scripts_Utils::read_json_file($destinations_dir . $destination . '_fr.json');
            foreach ($dist["articles"] as $adresse) {
                $post=AFTG_Helper::get_adresse_post_by_origin_id($adresse['id'],'fr');

                $post_id = ($post && isset($post)) ? $post->ID : null;
                $post_exerpt = $adresse["content"]["introduction"]["small"];
                echo($adresse["content"]["title"].'-----'. $adresse_slug." ID :" .$post_id."</br>");
                if ( $post_id && $post->post_excerpt=="") {
                    echo "IN"."</br>";
                    if ($post_id) {
                        echo "IN POSTID"."</br>";

                        $the_post = array(
                            'ID'           => $post_id, //the ID of the Post
                            'post_excerpt' => $post_exerpt,
                        );
                       echo  wp_update_post($the_post).'</br>';
                    }
                }
            }
        }
    }
}
