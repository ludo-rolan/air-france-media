<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

global $wpdb;

$options = getopt('', ['language::']);
$language = !empty($options['language']) ? $options['language'] : 'fr';

$options = getopt('', ['postid::']);
$postid = isset($options['postid']) ?  $options['postid'] : NULL;

if ($postid != NULL) {
    $sql = "SELECT p.ID, p.post_content FROM wp_posts as p WHERE p.post_type = 'adresse' AND p.post_status = 'publish' AND p.ID = " . $postid . ';';
} else {
    $sql = "SELECT p.ID, p.post_content FROM wp_posts as p WHERE p.post_type = 'adresse' AND p.post_status = 'publish';";
}

$results = $wpdb->get_results($sql, ARRAY_A);

foreach ($results as $post) {
    $rectified = preg_replace('#<a href=".*/common/travel-guide.*?>([^>]*)</a>#i', '$1', $post['post_content']);
    $encoding = str_replace("'", "\'", $rectified);
    $update_query = "UPDATE wp_posts SET post_content = '" . $encoding . "' WHERE wp_posts.ID = " . $post['ID'];
    $wpdb->get_results($update_query);
    println_flush("post ID = " . $post['ID'] . ' updated');
}
