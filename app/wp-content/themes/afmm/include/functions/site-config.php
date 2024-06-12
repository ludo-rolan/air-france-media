<?php
global $site_config;
$ga_id = ICL_LANGUAGE_CODE == 'fr' ? 'UA-219112111-1' : 'UA-219112111-4';
$site_config = (!empty($site_config)) ? $site_config : [];

$site_config= array_merge($site_config, [
	"google_analytics_id" => $ga_id,
	'mobile_menu_icon' => STYLESHEET_DIR_URI . '/assets/img/mobile-burger-icon.png',
	'search_icon' => STYLESHEET_DIR_URI . '/assets/img/search-solid.svg',
	'microphone_img' => STYLESHEET_DIR_URI . '/assets/img/microphone-img.png',
	'play_icon' => STYLESHEET_DIR_URI . '/assets/img/play_icon.png',
	'hp_carousel_nav_left' => STYLESHEET_DIR_URI . '/assets/img/hp_carousel_nav_left.svg',
	'hp_carousel_nav_right' => STYLESHEET_DIR_URI . '/assets/img/hp_carousel_nav_right.svg',
	'carousel_pattern' => STYLESHEET_DIR_URI . '/assets/img/carousel_pattern.png',
	'hp_cats'=> array('a-la-une'=>'À LA UNE',
	                  'inspirations'=>'INSPIRATIONS','evasion'=>"ÉVASION",
	                  'styles'=>'STYLES','goût'=>'GOÛTS','a-bord'=>'À BORD',
	                  'travel-guide'=>'TRAVEL GUIDE'),
	'site_url' => site_url(),
	'cheetah_nl' => array(
		'apiPostId'	=> 141,
		'prefix' => 'afmm',
		'acronym'=> 'afmm',
		'optin' 		=> array(
			'optin_edito'=> array(
				'date_opt' 		=> 'date_optin_edito',
				'src_opt' 		=> 'NL',
			),
		),
		'categorie_cheetah' => 'en-vols',
		'ref_id_rmp'   	=>	array('Air France Edito' => 21),
		'footer_nl_info' => array(
			'msg'   => "Ne ratez plus rien de l'actualité de Air France",
			'class' => 'afmm',
			'optin' => array('optin_edito'),
		),
	),
	'afmm_cache' => array(
		'maillageAjax' => array('key'=>'maillage_ajax_','time' => 60 * 30),
		'hp_inspiration' =>  array('key' => 'posts_preview_elem_inspiration', 'time' => 60*30),
		'hp_podcast' => array('key' => 'hp_options_cinq_post_podcast','time' => 60*30),
		'hp_video' =>  array('key' => 'hp_options_cinq_post_video','time' => 60*30),
		'hp_hero' => array('key' => 'hp_bandeau_image','time' => 60*30),
		'hp_category_alune' => array('key' => 'hp_category_detail_alune','time' => 60*30),
		'hp_post_alune' => array('key' =>'hp_big_post_alune', 'time'  => 60*30),
		'posts_preview_elem' => array('key' =>'posts_preview_elem_bloc_', 'time'  => 60*30),
		'hp_posts_preview_wide' => array('key' =>'hp_posts_preview_wide_', 'time'  => 60*30),
		'hp-category_posts' => array('key' =>'hp-category_posts_', 'time'  => 60*30),
		'block_partager' => array('key' =>'block_partager_', 'time'  => 60*30),
		'hp_villes' => array('key' => 'hp_options_cinq_ville_tg','time' => 60*30),
		'gallery_monetisation' => array('key' => 'gallery_monetisation','time' => 60*60),
		'ip_info' => array('key' => Localisation_Geoip::get_the_ip(), 'time' => 60*60),
		"search"=> array('key' => 'afmm_search','time' => 60*60),

	),
	'search_wanted_post_types'=>['post','travel-guide'],
	'taxonomies_with_images' =>['partenaires'],
	"meta_visiter_endroit_post_type" =>['post', 'travel-guide'],
	"filter_post"=>array(
		['post_type'=>'post',
		'title'=>'Type article',
		'post_meta'=>'post_display_type_name',
		'values'=>array(
		'LONGFORM' => 'LONG FORM',
		'EDITO' => 'EDITO',
		'DIAPO' => 'DIAPO',
		'DIAPO ACQUISITION' => 'DIAPO ACQUISITION',
		)
		]),
	"meta_post_type_options" => [
		'LONGFORM',
		'EDITO',
		'DIAPO',
		'DIAPO ACQUISITION' => 'DIAPO ACQUISITION',
	],
	"pages" =>array(
		[
			'pagename'=>'dailyfeed',
			'titre'=>'Les derniers contenus publiés :',
			'titre_noresults'=>"Aucun contenu n'a été publié",
			'args' => array(
				'post_type' => 'post',
				'posts_per_page' =>isset($_GET['number']) ? $_GET['number'] :30,
				'date_query' => array(
					array(
						'day'=>(isset($_GET['date'])) ? date('d',strtotime($_GET['date'])) :getdate()['mday'],
    					'month'=>(isset($_GET['date'])) ? date('m',strtotime($_GET['date'])) :getdate()['mon'],
    					'year'=>(isset($_GET['date'])) ? date('Y',strtotime($_GET['date'])) :getdate()['year'],
    
					),
				),
				'suppress_filters'=>false,
			),
		],
		[
			'pagename'=>'flux',
			'titre'=>'Les derniers contenus publiés :',
			'titre_noresults'=>"Aucun contenu n'a été publié",
			'args' => array(
				'post_type' => 'post',
				'meta_query' => array(
					array(
						'key'   => 'chaud_froid_type',
						'value' => isset($_GET['type']) ? $_GET['type'] :'CHAUD',
					)
				),
				'posts_per_page' =>isset($_GET['number']) ? $_GET['number'] :6,
				'date_query' => array(
					array(
						'day'=>(isset($_GET['date'])) ? date('d',strtotime($_GET['date'])) :getdate()['mday'],
    					'month'=>(isset($_GET['date'])) ? date('m',strtotime($_GET['date'])) :getdate()['mon'],
    					'year'=>(isset($_GET['date'])) ? date('Y',strtotime($_GET['date'])) :getdate()['year'],
    
					),
				),
				'suppress_filters'=>false,
			),
		]

		),
]);

if (defined('ICL_LANGUAGE_CODE')){
	foreach ($site_config['afmm_cache'] as $key => $value) {
		$site_config['afmm_cache'][$key]['key'] = ICL_LANGUAGE_CODE ."_". $value['key'];
	}
}
$site_config['afmm_user_location'] = get_data_from_cache($site_config['afmm_cache']['ip_info']['key'],'ip_info',$site_config['afmm_cache']['ip_info']['time'],function(){
	return	Localisation_Geoip::get_country_language();
});
