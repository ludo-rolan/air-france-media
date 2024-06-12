<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';

$countries_file = DATA_DIR . 'countries_full.json';
$description_file= DATA_DIR . 'countries/list_country_baseline.json';
$countries = Scripts_Utils::read_json_file($countries_file);
$descriptions = Scripts_Utils::read_json_file($description_file);

foreach ($descriptions as $key=>$description){
    println_flush($key);
    $args = array(
        'hide_empty' => false, 
        'meta_query' => array(
            array(
               'key'       => 'code',
               'value'     => $key,
               'compare'   => '='
            )
        ),
        'taxonomy'  => 'destinations',
        );
    $terms = get_terms( $args );
    if(count($terms)){
        if(isset($description['fr']['baseline'])){
            wp_update_term($terms[0]->term_id,"destinations",array(
                "description"=>$description['fr']['baseline']
            ));
        }
        else {
            println_flush($key .'=> francais inexistant' );

        }
    }
}
