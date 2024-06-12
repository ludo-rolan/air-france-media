<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';
$countries_file = DATA_DIR . 'countries_full.json';
$description_file= DATA_DIR . 'countries/list_country_baseline.json';
$countries = Scripts_Utils::read_json_file($countries_file);
$descriptions = Scripts_Utils::read_json_file($description_file);

global $sitepress;
$sitepress->switch_lang('en');

foreach ($descriptions as $key=>$description){
	println_flush($key);
	$term = AFTG_Helper::get_term_destination_by_iata_code($key,"en");
	if($term){
		var_dump($description['en']['baseline']);
		if(isset($description['en']['baseline'])){
			wp_update_term($term->term_id,"destinations",array(
				"description"=>$description['en']['baseline']
			));
			var_dump(get_term_by('id',$term->term_id,'destinations'));
		}
		else {
			println_flush($key .'=> Anglais inexistant' );
		}
	}
}
