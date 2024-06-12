<?php 
global $site_config;
$gtag_id = ICL_LANGUAGE_CODE == 'fr' ? 'UA-219112111-2':'UA-219112111-3';

$site_config = (!empty($site_config)) ? $site_config : [];

$site_config = array_merge($site_config, array(
	"google_analytics_id" => $gtag_id,
    "taxonomies_with_images"=>['destinations', 'envies', 'mois', 'aux_alentours', 'partenaires'],
    "meta_visiter_endroit_post_type" =>['adresse'],
    "site_url" => site_url(),
	'tg_hp_destination' => [
			'img' => STYLESHEET_DIR_URI . '/assets/img/tg_hp_destination_img.webp',
			'settings' => STYLESHEET_DIR_URI . '/assets/img/settings.svg',
			'earth' => STYLESHEET_DIR_URI . '/assets/img/earth.svg',
			'calender' => STYLESHEET_DIR_URI . '/assets/img/destination_calender.svg',
			'currency' => STYLESHEET_DIR_URI . '/assets/img/currency.svg',
			'plane' => STYLESHEET_DIR_URI . '/assets/img/aftg-plane.svg',
	],
	'tg_carnet_voyage' => [
		'trash' => STYLESHEET_DIR_URI . '/assets/img/trash.png',
	],
	'aftg_cache' => array(
		'block_partager' => array('key' =>'block_partager_', 'time'  => 60*60),
		'book_fly_template' => array('key' =>'book_fly_template_destination', 'time'  => 60*60),
		'hp_destinations_block' => array('key' =>'hp_destinations_block', 'time'  => 60*60),
		'results_page_destinations' => array('key' =>'results_page_destinations', 'time'  => 60*60),
		'adresses_block' => array('key'=>'addresses_page','time'=>60*60),
		'maillage_afmm_posts' => array('key' =>'maillage_afmm_posts', 'time'  => 60*60),
		'destination_external_id' => array('key' =>'destination_external_id', 'time'  => 60*60),
		'ip_info' => array('key' => Localisation_Geoip::get_the_ip(), 'time' => 60*60),
		'breadcrumb_alentours_bloc' => array('key' => "breadcrumb_alentours", 'time' => 60*60),
		'ajax_destination_child_term' => array('key' => 'destination_child_term', 'time' => 60*60*5),
		'ajax_autocomplete_result' => array('key' => 'ajax_autocomplete_result', 'time' => 60*60*5),
		'readmore_aftg_bloc' => array('key' => 'readmore_aftg_bloc', 'time' => 60*60),
	),
	'months_fr' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    'desires_fr' => ['Plage', 'City trip', 'Arts et cultures', 'En famille', 'Gastronomie', 'Golf', 'Ski et montagne', 'Sport et nature', 'Vie nocturne', 'Road trip', 'En amoureux', 'Surf et plongée', 'Shoppping', 'Soleil', 'Spas et bien-être', 'Insolite'],
	
));

if (defined('ICL_LANGUAGE_CODE')){
	foreach ($site_config['aftg_cache'] as $key => $value) {
		$site_config['aftg_cache'][$key]['key'] = ICL_LANGUAGE_CODE ."_". $value['key'];
	}
}

$site_config['aftg_user_location'] =  get_data_from_cache($site_config['aftg_cache']['ip_info']['key'],'ip_info',$site_config['aftg_cache']['ip_info']['time'],function(){
	return	Localisation_Geoip::get_country_language();
});

$site_config['dfp-position'] = array(
//vérifié	
'homepage' => array(
	'ad1' => 'masthead_haut',
	'ad2' => 'mpu_haut',
	'mobile_ad1' => 'mobile_1',
	'mobile_ad2' => 'mobile_2',

),
//vérifié .
'single_destination' => array(
	'ad1' => 'masthead_haut',
	'ad2' => 'mpu_haut',
	'ad3' => 'mpu_milieu_2',
	'ad4' => 'mpu_milieu_3',
	'mobile_ad1' => 'mobile_1',
	'mobile_ad2' => 'mobile_2',

),
//vérifié 
'info_pratique' => array(
	'ad1' => 'masthead_haut',
),
//vérifié .
'taxonomy_destinations' => array(
	'ad1' => 'masthead_haut',
),

//vérifie
'tax_aux_alentours' => array(
	'ad1' => 'masthead_haut',
	'ad2' => 'vignette_haut',
	'ad3' => 'mpu_haut',
),
//vérifié
'archive-destination' => array(
	'ad1' => 'masthead_haut',
	'ad2' => 'mpu_haut',
),
//verifié
'archive-adresse' => array(
	'ad1' => 'masthead_haut',
),


//vérifié 
'single' => array(
	'ad1' => 'masthead_haut',
	'ad2' => 'mpu_haut',
	'ad3' => 'mpu_milieu',

),
);