<?php
global $site_config;

$site_config = (!empty($site_config)) ? $site_config : [];

global $site_config_js ;


if(rw_is_mobile()){
	$site_config_js['nginx_mobile'] = 1 ;
}elseif(rw_is_tablet()){
	$site_config_js['nginx_tablet'] = 1 ;

}else{
	$site_config_js['nginx_desktop'] = 1 ;
}
$current_language = 'fr';
if(function_exists('icl_object_id')){
	$current_language = ICL_LANGUAGE_CODE;
}
$site_config = array_merge($site_config,[
	'inspirations_icon' => [
		'avion-black' => AF_THEME_DIR_URI . '/assets/img/avion-black.svg',
		'avion-on' => AF_THEME_DIR_URI . '/assets/img/avion-on.svg',
		'avion-off' => AF_THEME_DIR_URI . '/assets/img/avion-off.svg',
		'avion-border' => AF_THEME_DIR_URI . '/assets/img/avion-border.svg',
		'calender' => AF_THEME_DIR_URI . '/assets/img/calender.svg',
		'handshake' => AF_THEME_DIR_URI . '/assets/img/handshake.svg'

	],
	'ga_analytics'=>[
		'afmm_fr' => 'ga:259940425',
		'afmm_en' => 'ga:262417606',
		'aftg_fr' => 'ga:261833294',
		'aftg_en' => 'ga:262414577',
	],
	'logo_header' => AF_THEME_DIR_URI . '/assets/img/LogoEnvols-blue.png',
	'logo_nl' => AF_THEME_DIR_URI . '/assets/img/LogoEnvols-noir_v3.png',
	'logo_footer' => AF_THEME_DIR_URI . '/assets/img/LogoEnvols_v2.png',
	'theme_colors'=>array(
		'#091852','#d3182a','#000000','#FFFFFF',
	),
	'visitez_endroit' => [
		// param lang et country va se changer en utlisant js donc nvrm pour les params par défault
		'reservation_site' => 'https://wwws.airfrance.fr/exchange/deeplink?language='.ICL_LANGUAGE_CODE.'&country=fr&target=/',
		'bg_color_vignette' => '#D3182A',
		'image_vignette' => AF_THEME_DIR_URI . '/assets/img/avion-white.png',
		'titre_vignette' => __('Réservez votre vol',AFMM_TERMS),
	],
	"site_url" => site_url(),
	"mail" => [
		'subject' => 'Découvrez l\'article que je viens de lire : ',
		'content' => 'Découvrez l\'article que je viens de lire : '
	],
	'afmm' => ['preprod' => 'https://www.en-vols.com/', 'prod' => 'https://www.en-vols.com/'],
	'aftg' => ['preprod' => 'https://guide.en-vols.com/', 'prod' => 'https://guide.en-vols.com/'],
	'ismobile' => rw_is_mobile(),
	'afcore_cache' => array(
		'autocomplete_destinations' => array('key' =>'autocomplete_destinations', 'time'  => 60*60),
		'autocomplete_airports' => array('key' =>'autocomplete_airports', 'time'  => 60*60),
		'most_popular' => array('key'=>'mostpopular','time'=>24 * 60 * 60),
		// set cache duration 24h (low changes => header & footer)
		'maillage' => ['key'=>'maillage_between_aftg_afmm','time'=>24 * 60 * 60],
	),
	'pagination' => [
		'left' => AF_THEME_DIR_URI . '/assets/img/pagination_left_arrow.svg',
		'right' => AF_THEME_DIR_URI . '/assets/img/pagination_right_arrow.svg'
	],
	'langue_selectionner' => $current_language,

]);

if (defined('ICL_LANGUAGE_CODE')){
	foreach ($site_config['afcore_cache'] as $key => $value) {
		$site_config['afcore_cache'][$key]['key'] = ICL_LANGUAGE_CODE ."_". $value['key'];
	}
}

$site_config['origin_url'] = IS_PREPROD ? $site_config['afmm']['preprod'] : $site_config['afmm']['prod'];