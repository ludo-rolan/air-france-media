<?php




function println_flush($s)
{

  if (defined('STDIN')) {
    print_flush($s . "\n");
  } else {
    print_flush($s . "</br>");
  }
}

function print_flush($message)
{
  ob_start();
  echo $message;
  ob_flush();
  flush();
}

function get_destination_id($iata_code)
{
  $args = array(
    'numberposts' => 1,
    'post_type' => 'destination',
    'meta_query' => array(
      array(
        'key'       => 'origin_code',
        'value'     => $iata_code,
        'compare'   => '='
      )
    )
  );
  $posts = get_posts($args);
  if (count($posts)) {
    //println_flush("ID: " . $posts[0]->ID . " Slug: " . $iata_code);
    return $posts[0]->ID;
  } else {
    return 0;
  }
}

function update_is_not_single_meta_box($post_id, $json_part, $keys, $metabox_name)
{
  $meta = [];
  echo  "meta_box_name:" . $metabox_name . "\n<br>";
  foreach ($json_part as $key => $val) {
    echo '"' . $key . '",';
  }
  foreach ($keys as $meta_key) {


    if (isset($json_part[$meta_key])) {
      $meta[$metabox_name . '_' . $meta_key] = $json_part[$meta_key];
    }
  }
  echo "<br>\n";
  update_post_meta($post_id, $metabox_name, $meta);
}
