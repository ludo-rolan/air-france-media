<?php
global $site_config;
$gtag_id = ICL_LANGUAGE_CODE == 'fr' ? 'UA-219112111-2':'UA-219112111-3';
$pages_types_dfp =array(
	'hp' =>array( 'mpu_haut','masthead_haut','vignette_haut','mobile_1', 'mobile_2'),
	'rg'=> array(  'mpu_haut', 'mpu_milieu','masthead_haut','mpu_milieu_2','mpu_milieu_3','mobile_1', 'mobile_2'),
  );
$plan_tagagge_dfp =  array(
  "hp" => array( 'id' => "home"  ), 
  "divers" => array( 'id' => "RG"),
);
$formats_lazyloading =[] ;
$formats_lazyloading = array('mobile_1','mobile_2', 'mobile_3', 'mobile_4', 'mobile_5', 'mobile_6', 'mobile_7', 'mobile_8', 'mobile_9', 'mobile_10', 'mobile_11', 'mobile_12');	
$lg=apply_filters( 'wpml_current_language', NULL );
$lg?$id_lang=$lg:$id_lang="fr";
// Relay properties
$pays_langue_info = $site_config['aftg_user_location'];
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
	'dfp' => array(
		'config' => array(
			'dfp_id_account' => "46980923/travelguide.$id_lang-web",
			'pages_types' => $pages_types_dfp,
			'plan_tagagge' => $plan_tagagge_dfp,
			'formats_lazyloading' => $formats_lazyloading,

		),
	),
	'google_analytics' => array(
		'config' => array(
			'google_analytics_gtags' => array(
				'gtag' => $gtag_id,
				'gtag-reworld' => 'UA-192639368-1',
				'gtag-reworld-0627' => 'UA-223816716-3',
				'gtag-reworld-0625' => 'UA-223816716-2'
			)

		)
	),
	'wemap' => array(
        'desc'                    => 'wemap',
        'class_name'             => 'Wemap',
        'implementation'         => 'wemap.php',
        'callback'                 => 'wemap_implementation',
        'action' => array('wp_head', 1),
        'is_tag'                 => true,
		'action_admin' => 'admin_enqueue_scripts',
		'callback_admin' => 'wemap_implementation',
    ),

	'wemapPinPoints' => array(
        'desc'	=> 'ce partenaire est le responsable de la synchronisation des poi (cpt adresse) avec WEMAP',
        'class_name'	=> 'WemapPinpoint',
        'implementation'	=> 'pinpoints.php',
		'default_activation'	=> 1,
        'is_tag'	=> true,
		'callback_admin' =>	'init',
    ),
);
