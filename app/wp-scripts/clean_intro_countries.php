<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

/**
 * THIS IS FOR CLEANING THE COUNTRIES DESCRIPTION TERMS
 * 
 */

function update_description_aftg($lang)
{
    global $sitepress;
    $sitepress->switch_lang($lang);

    println_flush('------- '.$lang.' -------');

    //UPDATE ALL COUNTRIES
    $continents = get_terms('destinations', ['parent' => 0]);
    foreach ($continents as $continent) {
        $countries = get_terms('destinations', ['parent' => $continent->term_id]);
        foreach ($countries as $country) {
            // IMPORTANT INFO = DEFAULT DESCRIPTION FIELD HAS AN EMPTY STRING
            $update = wp_update_term($country->term_id, 'destinations', ['description' => '']);
            is_wp_error($update) ? println_flush($country->name . ' already has default value') : println_flush($country->name . ' term is updated');
        }
    }
}

update_description_aftg('fr');
update_description_aftg('en');