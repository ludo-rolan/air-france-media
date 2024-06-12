<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

global $wpdb;
$counter = 0;

$credits = DATA_DIR . 'credit_poi/poi.json';
$data = Scripts_Utils::read_json_file($credits);
foreach ($data['Sheet1'] as $poi) {
    $posts = get_posts(
        [
            "post_type" => "adresse",
            "meta_key" => "articleUrl",
            "meta_value" => $poi["URL"],
        ]
    );
    foreach($posts as $post)
    {
        $attachmentId = get_post_thumbnail_id($post->ID);
        $copyright = str_replace('"',"'",$poi['Crédits']);
        $query = 'UPDATE `wp_posts` SET `post_excerpt` = "'.$copyright.'" WHERE `wp_posts`.`ID` = '.$attachmentId.';';
        $wpdb->get_results($query);
        $counter ++;
    }
}

println_flush($counter." images updated");
