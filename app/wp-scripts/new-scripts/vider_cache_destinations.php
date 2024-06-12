<?php

const PROJECT_NAME = 'aftg';
require '../init.php';
$args = array(
  'post_type'  => 'destination',
  'nopaging' => true
);
$url = "https://guide.en-vols.com/";
$destinations = get_posts($args);
foreach ($destinations as $destination) {
  $link = substr(get_permalink($destination->ID),0,-1);
  $link=str_replace($url,$url."purge/",$link);
  $ch = curl_init();
  curl_setopt(
    $ch,
    CURLOPT_URL,
    $link
  );
  $content = curl_exec($ch);
  curl_close($ch);
  echo "Purged :" . $link;
  
}
