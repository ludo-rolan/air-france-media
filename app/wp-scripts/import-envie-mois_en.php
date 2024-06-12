<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';

$file_ranking = DATA_DIR . 'ranking/export_ranking_criteria.csv';

//Récuperation des taxs slug
if (($open = fopen($file_ranking, "r")) !== FALSE) {
	$count = 0;
	while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
		if ($count != 0) {
			$array[] = $data['1'];
		}
		$count++;
	}

	fclose($open);
}
$months = array(
	'january',
	'february',
	'march',
	'april',
	'may',
	'june',
	'july',
	'august',
	'september',
	'october',
	'november', 'december'
);
$array_tax = array_unique($array);
//Sauvegarde des taxs slugs .
foreach (array_unique($array) as $elem) {
	$tax_slug = Scripts_Utils::slugify($elem);
	in_array($tax_slug, $months) ? $type_tax = "mois" : $type_tax = "envies";
	$term = get_term_by('slug', $tax_slug, $type_tax);
	$term_id = ($term) ? $term->term_id : null;
	if (!$term) {
		$term = wp_insert_term($tax_slug, $type_tax, array(
			'slug' => $tax_slug,

		));
		if (is_wp_error($term)) {
			println_flush("Error creating $type_tax : $tax_slug \n </br>");
		}
		$term_id = $term["term_id"];
		println_flush("Created $type_tax: $tax_slug \n </br>");
	} else {
		println_flush("$type_tax: $tax_slug is already created \n </br>");
	}
}

//récuperation des taxo
$months_terms = get_terms(array('taxonomy' => 'mois', 'hide_empty' => false));
$envies_terms = get_terms(array('taxonomy' => 'envies', 'hide_empty' => false));


$months_terms = get_slug_id_terms($months_terms);
$envies_terms = get_slug_id_terms($envies_terms);

//récuperation des ranking

$iata_code = '';
$found = 0;
$not_found = 0;
$destination_infos = [];
$destination_id = 0;

$not_founds=[];
global $sitepress;
$sitepress->switch_lang("en");

if (($open = fopen($file_ranking, "r")) !== FALSE) {
	$count = 0;
	while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
		$tax_slug = $data['1'];
		if ($count != 0) {
			if ($iata_code !== $data['0']) {
				$iata_code = $data['0'];
				$destination_data = AFTG_Helper::get_dastination_post_by_iata_code($iata_code,"en");
				$destination_id = $destination_data->ID;
				$destination_info[$destination_id] = [];
				if ($destination_id) {
					$destination_info[$destination_id] = [];
					$destination_info[$destination_id]['mois'] = [];
					$destination_info[$destination_id]['envies'] = [];
					$destination_info[$destination_id]['months_to_link'] = [];
					$destination_info[$destination_id]['months_to_detach'] = [];
					$destination_info[$destination_id]['envies_to_link'] = [];
					$destination_info[$destination_id]['envies_to_detach'] = [];
					$found += 1;
				} else {
					$not_found += 1;
					$not_founds[]=$data['0'];
				}
			}
			if ($destination_id) {
				if (in_array(strtolower($tax_slug), $months)) {
					$destination_info[$destination_id]["mois"]["mois_" . $months_terms[$tax_slug]] = $data['2'];
					Ranking::insert_ranking($destination_id, $months_terms[$tax_slug], $data['2'], "mois", strtolower($tax_slug));
					if ($data['2'] > 0) {
						$destination_info[$destination_id]['months_to_link'][] = $months_terms[$tax_slug];
					} else {
						$destination_info[$destination_id]['months_to_detach'][] = $months_terms[$tax_slug];
					}
				} else {
					$destination_info[$destination_id]["envies"]["envies_" . $envies_terms[$tax_slug]] = $data['2'];
					Ranking::insert_ranking($destination_id, $envies_terms[$tax_slug], $data['2'], "envies", strtolower($tax_slug));
					if ($data['2'] > 0) {
						$destination_info[$destination_id]['envies_to_link'][] = $envies_terms[$tax_slug];
					} else {
						$destination_info[$destination_id]['envies_to_detach'][] = $envies_terms[$tax_slug];
					}
				}
			}
		}
		$count++;
	}

	fclose($open);
}



foreach ($destination_info as $id => $destination_info) {
	if ($id) {
		println_flush("ID: " . $id);
		update_post_meta($id, "mois", $destination_info['mois']);
		update_post_meta($id, "envies", $destination_info['envies']);
		wp_remove_object_terms($id, $destination_info['months_to_detach'], "mois");
		wp_set_object_terms($id, $destination_info['months_to_link'], "mois");
		wp_remove_object_terms($id, $destination_info['envies_to_detach'], "envies");
		wp_set_object_terms($id, $destination_info['envies_to_link'], "envies");
	}
}


println_flush('Found' . $found);
println_flush('Not Found' . $not_found);


function get_slug_id_terms($terms)
{
	$array = [];
	foreach ($terms as $elem) {
		if (!empty($elem->slug)) {
			$array[strtoupper($elem->slug)] = $elem->term_id;
		}
	}
	return $array;
}




