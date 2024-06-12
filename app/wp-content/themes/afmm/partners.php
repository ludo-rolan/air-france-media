<?php
global $site_config;
$ga_id = ICL_LANGUAGE_CODE == 'fr' ? 'UA-219112111-1' : 'UA-219112111-4';
$pages_types_dfp =array(
	'hp' =>array('habillage', 'masthead_haut','masthead_bas','mpu_haut','native','mobile_1', 'mobile_2', 'mobile_3', 'native_mobile'),
	'hp_rubrique' =>array('habillage', 'masthead_haut','mpu_haut','native','mobile_1', 'mobile_2', 'native_mobile'),
	'rg'=> array( 'habillage','masthead_haut','masthead_bas','mpu_haut', 'mpu_milieu' ,'mobile_1', 'mobile_2'),
	'search'=> array( 'habillage','masthead_haut','mpu_haut', 'mpu_milieu','native' ,'mobile_1', 'mobile_2', 'native_mobile'),
	'diapo_monetisation'=> array( 'habillage','masthead_haut','masthead_bas','mpu_haut', 'mpu_milieu' ,'mobile_1', 'mobile_2'),
	#'diapo_monetisation'=> array( 'habillage', 'mpu_haut', 'mpu_milieu','masthead_haut','mobile_1', 'mobile_2'),
  );
$plan_tagagge_dfp =  array(
  "hp" => array( 'id' => "home"  ), 
  "divers" => array( 'id' => "RG"),
);
$formats_lazyloading =[] ;
//if($site_config['ismobile']){
$formats_lazyloading = array('mobile_2') ;	
//}
$lg=apply_filters( 'wpml_current_language', NULL );
$lg?$id_lang=$lg:$id_lang="fr";

// Relay properties
$pays_langue_info = $site_config['afmm_user_location'];
$country_iso_code = isset($pays_langue_info['pays_iso_code']) ? $pays_langue_info['pays_iso_code'] : '';

$relay_42_properties = array(
	'Country' => $country_iso_code,
);
$partners = array(
	'didomi' => array(
	),
	'relay_42' => array(
		'config' => array(
			'properties' => $relay_42_properties,
		),
	),
	'google_analytics' => array(
		'config' => array(
			'google_analytics_gtags' => array(
				'gtag' => $ga_id,
				'gtag-reworld' => 'UA-192639368-1',
				'gtag-reworld-0626' => 'UA-223816716-1',
				'gtag-reworld-0625' => 'UA-223816716-2'
			)

		)
	),
	'dfp' => array(
		'config' => array(
			'dfp_id_account' => "46980923/afmm.$id_lang-web",
			'pages_types' => $pages_types_dfp,
			'plan_tagagge' => $plan_tagagge_dfp,
			'formats_lazyloading' => $formats_lazyloading,

		),
	),



);
