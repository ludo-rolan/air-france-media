<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

/*
*   setup title for admin requirements
*/

function manage_admin_proc($lang)
{
    global $sitepress;
    $sitepress->switch_lang($lang);

    $args = [
        'post_type' => 'destination',
        'nopaging' => true,
    ];

    $allPosts = new WP_Query($args);

    $counter = 0;

    foreach ($allPosts->posts as $post) {

        $counter++;

        $titles = get_post_meta($post->ID, 'titles', true);

        $city_term = get_the_terms($post->ID, 'destinations')[0];
        $country_term = get_term($city_term->parent, 'destinations')->name;

        $lang == 'fr' ?  $titles['titles_administrativeProcedures'] = 'CONDITIONS POUR ENTRER SUR LE TERRITOIRE DU ' . $country_term : $titles['titles_administrativeProcedures'] = 'Entry requirements for ' . $country_term;

        $updated = update_post_meta($post->ID, 'titles', $titles);
        $updated === true ? println_flush($post->post_title . ' updated') : println_flush('has content ' . $post->post_title);
    }

    println_flush("------------------------- looped for " . $counter . " Posts -------------------------");
}

manage_admin_proc('fr');
manage_admin_proc('en');
