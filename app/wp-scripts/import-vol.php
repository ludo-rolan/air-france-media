<?php

function importVol()
{
    require 'helper.php';
    require 'init.php';
    $file_vols = DATA_DIR . 'vols/export_vols.csv';
    ini_set('memory_limit', '2024M');
    ini_set('max_execution_time', '300');
    set_time_limit(300);
    
    println_flush("Début du script d'import des vols...");
    println_flush("Début d'extraction des données du fichier csv...");
    $array = [];
    if (($open = fopen($file_vols, "r")) !== FALSE) {
        $count = 0;
        while (($data = fgetcsv($open, 1000, ",")) !== FALSE ) {
            if ($count != 0) {
                $array[] = array_map('trim', $data);
            }
            $count++;
        }
        
        fclose($open);
    }
    println_flush("Fin d'extraction des données du fichier csv.");
    
    // Suppression des doublons si existent
    $array_vols = array_unique($array, SORT_REGULAR);
    
    // Enregistrer les aeroports
    $unique_airports = [];
    println_flush("Début d'insertion des aéropots...");
    foreach ($array_vols as $vol) {
        if(!in_array($vol[0], $unique_airports)) {
            array_push($unique_airports, $vol[0]);
            $city_name = get_city_name($vol[2]);
            Airport::save_airport($vol[0], $vol[1], str_replace('Intl', 'International Airport', $vol[2]), $vol[3], $city_name);
        }
        if(!in_array($vol[4], $unique_airports)) {
            array_push($unique_airports, $vol[4]);
            $city_name = get_city_name($vol[6]);
            Airport::save_airport($vol[4], $vol[5], str_replace('Intl', 'International Airport', $vol[6]), $vol[7], $city_name);
        }
    }
    println_flush("Fin d'insertion des aéropots...");
    
    // Enregistrer les vols
    global $wpdb;
    $table_name = $wpdb->prefix . "vol";
    $vol_added_count = 0;
    $vol_skipped_count = 0;
    println_flush("Début d'insertion des vols...");
    foreach ($array_vols as $vol) {
        $origin_code = $vol[0];
        $destination_code = $vol[4];
        
        // get id of origin
        $origin_id = $wpdb->get_col(
            $wpdb->prepare("SELECT id  FROM {$wpdb->prefix}airport WHERE code_iata=%s", $origin_code)
        );
        // get id of destination
        $destination_id = $wpdb->get_col(
            $wpdb->prepare("SELECT id  FROM {$wpdb->prefix}airport WHERE code_iata=%s", $destination_code)
        );
        
        // add destination to DB if it doesn't exist
        if( count($origin_id) && count($destination_id)) {
            Vol::save_vol($origin_id[0], $destination_id[0]);
            $vol_added_count++;
        }
        else {
            println_flush("Vol non créé, Code origin : {$origin_id[0]} , Code destination : {$destination_id[0]} .");
            $vol_skipped_count++;
        }  
    }
    println_flush("Fin d'insertion des vols...");
    println_flush("Nombre de vols insérés : {$vol_added_count}");
    println_flush("Nombre de vols non insérés : {$vol_skipped_count}");
    println_flush("Fin du script.");

}

function get_city_name($full_name) {
    $city_name = explode(" - ", $full_name)[0];
    $city_name_words = explode(" ", $city_name);
    if (count($city_name_words) > 1 && ctype_upper($city_name_words[count($city_name_words) - 1])) {
        $city_name = str_replace(' '.$city_name_words[count($city_name_words) - 1], "", $city_name);
    }

    return $city_name;
}