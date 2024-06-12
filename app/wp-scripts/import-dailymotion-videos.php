<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
$file_dailymotion = DATA_DIR . 'dailymotion/Videos-destinations-TG-habillage-EN-VOLS-16022022.csv';
global $sitepress;
$sitepress->switch_lang( "fr" );
//Récuperation des ids
$array = [];
if (($open = fopen($file_dailymotion, "r")) !== FALSE) {
    $count = 0;
    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
        if ($count != 0) {
            $array[$data['1']] =  $data['2'];
        }
        $count++;
    }
    fclose($open);
}
    $args = array(
        'numberposts' => -1,
        'post_type' => 'destination',
        'field'=>'ids', 
        'suppress_filters' => false
    );
    $posts = get_posts( $args );
    $meta = "dailymotion";
    $meta_value = array();
    foreach ($posts as $post) {
        $code_iata = get_post_meta( $post->ID, 'origin_code',true );
        if(!empty($code_iata)){
            $meta_value['dailymotion_id'] = $array[$code_iata];
            update_post_meta($post->ID, "dailymotion",($meta_value));
        }
    }

    $sitepress->switch_lang( "en" );

    $args = array(
        'numberposts' => -1,
        'post_type' => 'destination',
        'field'=>'ids', 
        'suppress_filters' => false
    );
    $posts = get_posts( $args );
    $meta = "dailymotion";
    $meta_value = array();
    foreach ($posts as $post) {
        $code_iata = get_post_meta( $post->ID, 'origin_code',true );
        if(!empty($code_iata)){
            $meta_value['dailymotion_id'] = $array[$code_iata];
            update_post_meta($post->ID, "dailymotion",($meta_value));
        }
        
    }
