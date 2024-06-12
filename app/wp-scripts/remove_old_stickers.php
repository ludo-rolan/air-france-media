<?php
const PROJECT_NAME = 'afmm';
require 'init.php';
require 'helper.php';

// get all published post (except travel-guide post)
$sql = 'SELECT p.ID FROM `wp_postmeta` as m , `wp_posts` as p WHERE p.ID = m.post_id AND m.meta_key = "visitez_endroit" AND p.post_status = "publish" AND p.post_type NOT IN ("travel-guide");';
$results = $wpdb->get_results($sql, ARRAY_A);

foreach ($results as $post) {
    // get post meta , specially for "visitez_endroit_lien_vignette3"
    $meta = get_post_meta((int)$post['ID'],'visitez_endroit',true);
    $meta_sticker_3 = $meta["visitez_endroit_lien_vignette3"];

    // if the post meta is set , we replace it by empty string 
    if($meta_sticker_3 != ""){
        $meta["visitez_endroit_lien_vignette3"] = "";
        update_post_meta($post['ID'],'visitez_endroit',$meta);
        println_flush($post['ID'].' '.'is updated');
    }
}