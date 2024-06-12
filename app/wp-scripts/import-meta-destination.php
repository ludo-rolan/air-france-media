<?php

function lunch_script_destination_meta($json, $post_id)
{

  $destination = $json['destination'];
  //origin et destination

  $keys = [
    'code', "label", "title", "pictureUrl",
    "pictureCaption", "pictureAccessibility"
  ];
  foreach ($keys as $meta_key) {
    $meta = $destination[$meta_key];
    update_post_meta($post_id, "origin_" . $meta_key, $meta);
  }
	//localisation
  $keys = [
    "latitude", "longitude", "countryCode", "regionCode"
  ];

  update_is_not_single_meta_box($post_id, $destination, $keys, "localisation");

  //tavelGuide
  $keys = [
    'isTravelGuideAvailable', "travelGuideUrl", "flightBookingUrl", "practicalInformationUrl", "regionCode"
  ];
  update_is_not_single_meta_box($post_id, $destination["travelGuide"], $keys, "travelGuide");

  //map
  $keys = [
    'isTravelGuideAvailable', "travelGuideUrl", "flightBookingUrl", "practicalInformationUrl", "regionCode"
  ];
  update_is_not_single_meta_box($post_id, $destination["travelGuide"], $keys, "travelGuide");

  //map
  $keys = [
    "introduction", "mapTitle", "mapId", "mapUrl", "mapTopic", "mapSource", "mapAccessibility",
  ];
  update_is_not_single_meta_box($post_id, $destination["content"]["map"], $keys, "map");

  //destination Spoken Languages
  $keys = [
    "weatherUnit", "temperatureValue", "pictogramUrlSvg", "pictogramUrl",
  ];
  update_is_not_single_meta_box($post_id, $json["practicalInformationCategories"]["destinationWeather"], $keys, "DestinationWeather");

  $keys = [
    "label", "symbol"
  ];
  update_is_not_single_meta_box($post_id, $json["practicalInformationCategories"]["currency"]['data'], $keys, "currency");
  //destinationSpokenLanguages
  $keys = [
    "code", "label"
  ];
  $code = '';
  $label = '';
  $destination_spoken_languages = $json["practicalInformationCategories"]["destinationSpokenLanguages"];
  if (count($destination_spoken_languages)) {
    foreach ($destination_spoken_languages as $lang) {
      if (isset($lang['code']) && isset($lang['label'])) {
        $code .= $lang['code'] . ",";
        $label .= $lang['label'] . ",";
      }
    }
    $array_to_save=['code'=>$code,'label'=>$label];
    update_is_not_single_meta_box($post_id,$array_to_save, $keys, "destinationSpokenLanguages");
  }
  //Titles 
  $keys = [
    "medical","goodToKnow","localCalendar","weather","currency","essentialPhrases",
    "touristInformation","airports","transportation"
  ];
    $titles_json=$json["practicalInformationCategories"]["titles"];
    $titles=[];
    foreach ($keys as $key) {
      if(isset($titles_json[$key]) && isset($titles_json[$key]["title"])){
          $titles[$key]=$titles_json[$key]["title"];
        }
      }
    update_is_not_single_meta_box($post_id,$titles, $keys, "titles");

	//main video
	$keys = [
		"videoTitle", "introduction", "videoPlayerId", "videoTopic", "videoAccessibility", "videoSource"
	];
	update_is_not_single_meta_box($post_id, $destination["content"]["mainVideo"], $keys, "mainVideo");
	// other videos
	update_post_meta($post_id, 'otherVideos', json_encode($destination["content"]["otherVideos"]));
	// slideShow
	$slide_gallery = "";
	foreach ($destination["content"]["slideShow"]["pictures"] as $slide) {
		$slide_id = Media_Helper::download_attachment_by_url($slide["imageUrl"], $slide["imageAccessibility"]);
		$slide_gallery .= $slide_id . ",";
	}
	// remove last comma
	$slide_gallery = substr($slide_gallery, 0, -1);
	$keys = [
		"topic", "title", "pictures"
	];
	$destination["content"]["slideShow"]["pictures"] = $slide_gallery;
	update_is_not_single_meta_box($post_id, $destination["content"]["slideShow"], $keys, "slideshow");
}

function update_is_not_single_meta_box($post_id, $json_part, $keys, $metabox_name)
{
  $meta = [];
  echo  "meta_box_name:" . $metabox_name . "\n<br>";
  foreach ($json_part as $key => $val) {
    echo '"' . $key . '",';
  }
  foreach ($keys as $meta_key) {


    if (isset($json_part[$meta_key])) {
      $meta[$metabox_name . '_' . $meta_key] = $json_part[$meta_key];
    }
  }
  echo "<br>\n";
  update_post_meta($post_id, $metabox_name, $meta);
}
