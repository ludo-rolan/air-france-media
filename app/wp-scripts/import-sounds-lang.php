<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
$file_sounds = DATA_DIR . 'sounds/list_sounds.csv';
define("WP_PATH", dirname(__FILE__) . '/../');

require_once(WP_PATH . 'wp-admin/includes/file.php');
require_once(WP_PATH . 'wp-admin/includes/media.php');
require_once(WP_PATH . 'wp-admin/includes/image.php');
println_flush('Début script des sounds');




$sound_urls = [];
$is_more_than_five_elems = [];
$sounds = [];

if (($open = fopen($file_sounds, "r")) !== FALSE) {
    $count = 0;
    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {

        if ($count != 0) {
            $line_sound = [];
            for ($i = 0; $i < 4; $i++) {
                if (isset($data[$i]) && $data[$i] != "") {
                    $line_sound[$i] = $data[$i];
                }
            }
            $sounds[] = $line_sound;
        }
        $count++;
    }

    fclose($open);
}
$found = 0;
/// sound_medias array with  id of media(sound) & sound link 
$sound_medias = [];
$sound_urls = array_unique(array_column($sounds, 3));
foreach ($sound_urls as $url) {
    if ($url) {
        $media_id = Media_Helper::download_attachment_by_url($url);
        if ($media_id) {
            $sound_medias[$media_id] = $url;
        }
        else {
            println_flush($url ."can't be downloaded");
        }
    }
}

$code_iata = '';
$languages = ["en", "fr", "de", "es", "it", "ja", "pt", "zh"];
$contents = [];
foreach ($sounds as $line) {
    if ($code_iata !== $line[0]) {
        $code_iata = $line[0];
        $destination_id = get_destination_id($code_iata);
        if ($destination_id) {
            $language_elem = $line[1];
            $contents[$code_iata] = [];
            $contents[$code_iata]["destination_id"] = $destination_id;
            $contents[$code_iata]["phrases"] = [];

            foreach ($languages as $lg) {
                $contents[$code_iata]["phrases"]["essentialPhrases_".$lg] = '';
            }
            $contents[$code_iata]["phrases"] ["essentialPhrases_".$language_elem] .= $line[2];
            if (isset($line[3]) && $line[3] != "") {
                $url = $line[3];
                $key = array_search($url, $sound_medias);
                if ($key !== false) {
                    $contents[$code_iata]["phrases"] ["essentialPhrases_".$language_elem] .= " [af_lang_audio id=$key] <br>";
                }
            }
        }
    } else if ($code_iata === $line[0] && $destination_id) {
        $language_elem = $line[1];
        $contents[$code_iata]["phrases"] ["essentialPhrases_".$language_elem] .= $line[2];
        if (isset($line[3]) && $line[3] != "") {
            $url = $line[3];
            $key = array_search($url, $sound_medias);
            if ($key !== false) {
                $contents[$code_iata]["phrases"] ["essentialPhrases_".$language_elem] .= " [af_lang_audio id=$key] <br>";
            }
        }
    }
}

foreach ($contents as $code_iata => $destination) {
    update_post_meta($destination["destination_id"],'essentialPhrases',$destination['phrases']);
}

