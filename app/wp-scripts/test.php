<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'aftg_helper.php';
$regions_file = DATA_DIR . 'regions_full_en.json';
$countries_file = DATA_DIR . 'countries_full_en.json';
$destinations_file = DATA_DIR . 'destinations_en.json';
$destinations_base_dir = DATA_DIR . 'destinations/';

$regions = Scripts_Utils::read_json_file($regions_file);
$countries = Scripts_Utils::read_json_file($countries_file);
$destinations = Scripts_Utils::read_json_file($destinations_file);




$destination_term_modal = array(
	"name" =>"Caraibes",
	"slug" => "caraibes",
//	"parent" => 0,
	"metas" => array(
		"code" => "CAR",
		"pictureUrl" => "",
		"latitude" => "",
		"longitude" => "",
		"isTravelGuideAvailable" => "",
		"travelGuideUrl" => ""
	)
);

$destination_post_model = [
	"post" => [
		"post_title" => "",
		"post_name" => "",
		"post_content" => "",
		"post_status" => "publish",
		"post_type" => "destination",
		"post_author" => 1,
	],
	"metas" => [
		"origin_label" => "",
		"origin_title" => "",
		"origin_pictureAccessibility" => "",
		"origin_pictureCaption" => "",
		"" => "",
	]
];




$term_model = [
	"name" => "",
	"slug" => "",
	"parent" => 0,
];


foreach ($regions as $region) {
	$term_model = [
		"name" => $region["label"],
		"slug" => Scripts_Utils::slugify($region["label"]),
		"parent" => 0,
	];
	$term_reg = AFTG_Helper::translate_taxonomy_destination_term_and_link(
		$region["code"],
		$term_model,
		"en"
	);

	foreach ( $region['countries'] as $country ) {
		$term_model = [
			"name" => $countries[$country]["label"],
			"slug" => Scripts_Utils::slugify($countries[$country]["label"]),
			"parent" => $term_reg,
		];
		if ($country == "HK") {
			$term_model["slug"] = "hong-kong-china";
		}
		elseif ($country == "PA") {
			$term_model["slug"] = "panama-america";
		}
		elseif ($country == "SG") {
			$term_model["slug"] = "singapour-asia";
		}
		elseif ($country == "SX") {
			$term_model["slug"] = "saint-martin-sx";
		}

		$term_cntr = AFTG_Helper::translate_taxonomy_destination_term_and_link(
			$country,
			$term_model,
			"en"
		);
		foreach ( $countries[$country]['destinations'] as $destination ) {
			$term_model = [
				"name" => $destinations[$destination]["label"],
				"slug" => $destinations[$destination]["canonicalName"],
				"parent" => $term_cntr,
			];
			AFTG_Helper::translate_taxonomy_destination_term_and_link(
				$destinations[$destination]["code"],
				$term_model,
				"en"
			);
		}
	}
}


foreach ($regions as $region) {
	foreach ( $region['countries'] as $country ) {
		foreach ( $countries[$country]['destinations'] as $destination ) {
			$dist =  Scripts_Utils::read_json_file($destinations_base_dir . $destination . '_en.json');
			$destination_data = $dist['destination'];
			$destination_post = [
				"post" => [
					"post_title" => $destination_data['label'],
					"post_name" => $destinations[$destination]["canonicalName"],
					"post_content" => $destination_data["content"]["intro"],
					"post_status" => "publish",
					"post_type" => "destination",
					"post_author" => 1,
				],
				"metas" => [
					"origin_code" => $destination,
					"origin_label" => $destination_data['label'],
					"origin_title" => $destination_data['title'],
					"origin_pictureAccessibility" => $destination_data['pictureAccessibility'],
					"origin_pictureCaption" => $destination_data['pictureCaption'],
				]
			];
			$post_id = AFTG_Helper::translate_posttype_destination($destination, $destination_post, "en");

//			var_dump($post_id);
		}
	}
}

foreach ($regions as $region) {
	foreach ( $region['countries'] as $country ) {
		foreach ( $countries[$country]['destinations'] as $destination ) {
			$dist =  Scripts_Utils::read_json_file($destinations_base_dir . $destination . '_en.json');
			$destination_data = $dist['destination'];
			foreach ($dist["articles"] as $adresse) {
				$adresse_content = $adresse["content"];
				$post_content = "";
				$poi_id = $adresse["id"];
				foreach ($adresse_content["paragraphs"] as $paragraph) {
					$post_content .= $paragraph["text"];
					if(isset($paragraph["picture"]) && !empty($paragraph["picture"]["url"])){
						$url = $paragraph["picture"]["url"];
						$attachment_id = Media_Helper::download_attachment_by_url($url);
						$attach_url =  Media_Helper::get_attachment_url($attachment_id);
						$post_content .= "<img src='".$attach_url."' />";
					}
				}
				$poi_type = ($adresse["type"] == "POI")?"adresse":'post';
				$poi_post = [
					"type" => $poi_type,
					"post" => [
						"post_title" => $adresse_content['title'],
//						"post_name" => Scripts_Utils::slugify($adresse_content['title']),
						"post_excerpt" => $adresse_content['introduction']["small"],
						"post_content" => $post_content,
						"post_status" => "publish",
						"post_type" => $poi_type,
						"post_author" => 1,
					],
					"metas" => []
				];
				$metas = array(
					"type",
					"id",
					"articleUrl",
					"city",
					"country",
					"region",
					"topic",
					"publicationDate",
					"isHidden",
					"isPartner",
					"isCrush",
				);
				foreach ($metas as $meta){
					if (!empty($adresse_content[$meta])){
						$poi_post["metas"][$meta] = $adresse_content[$meta];
					}
				}
				AFTG_Helper::translate_posttype_adresse($poi_id,$poi_post,"en");
			}
		}
	}
}
