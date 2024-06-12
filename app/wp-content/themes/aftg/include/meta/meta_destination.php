<?php
//[TR-0600] Optimisation du process d'intégration et modifications des destinations, POI */
$texts=["code","label","title"];
new MetaBox_Factory (
    array(
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "origin",
            "title"     => "origin",
            "position"  => "normal",
            "priority"  => "high",
        ),
		"is_single_meta"=>true,
        "fields"     => create_text_field_from_titles($texts)
    )
);
$titles=['latitude','longitude'];
new MetaBox_Factory (
    array(
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "localisation",
            "title"     => "localisation",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array_merge(create_text_field_from_titles($titles),array( array(
            "label"     => "map",
            "suffix_id" => "_map",
            "sanitize"  => false,
            'is_esc_html'=>true,
            "type"      => Text_MetaBox_Type::class,
        )))
            
    )
);
/* travelGuide [TR-0600] Optimisation du process d'intégration et modifications des destinations, POI */

new MetaBox_Factory (
    array(
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "slideshow",
            "title"     => "slideshow",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "title",
                "suffix_id" => "_title",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
			array(
                "label"     => "pictures",
                "suffix_id" => "_pictures",
                "sanitize"  => true,
                "type"      => Gallery_MetaBox_Type::class,
            ),
			
        )
    )
);

new MetaBox_Factory (
    array(
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "mainVideo",
            "title"     => "mainVideo",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "videoTitle",
                "suffix_id" => "_videoTitle",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "introduction",
                "suffix_id" => "_introduction",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
			array(
                "label"     => "videoPlayerId",
                "suffix_id" => "_videoPlayerId",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
	        array(
		        "label"     => "videoTopic",
		        "suffix_id" => "_videoTopic",
		        "sanitize"  => true,
		        "type"      => Text_MetaBox_Type::class,
	        ),
	        array(
		        "label"     => "videoAccessibility",
		        "suffix_id" => "_videoAccessibility",
		        "sanitize"  => true,
		        "type"      => Text_MetaBox_Type::class,
	        ),
			array(
                "label"     => "videoSource",
                "suffix_id" => "_videoSource",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
			
			
        )
    )
);

new MetaBox_Factory (
    array(
        "post_types" => array('destination'),
		"is_single_meta" => true,
        "meta_box"   => array(
            "id"        => "",
            "title"     => "otherVideos",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "videos",
                "suffix_id" => "otherVideos",
                "sanitize"  => false,
                "type"      => CodeEditor_MetaBox_Type::class,
            ),
        )
    )
);

$titles=['mapTitle','mapId','mapTopic'];

new MetaBox_Factory (
    array(
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "map",
            "title"     => "map",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => create_text_field_from_titles($titles)
        )
    );
$titles=['id'];
    new MetaBox_Factory (
        array(
            "post_types" => array('destination'),
            "meta_box"   => array(
                "id"        => "dailymotion",
                "title"     => "dailymotion",
                "position"  => "normal",
                "priority"  => "high",
            ),
            "fields"     => create_text_field_from_titles($titles)
            )
    );

$titles=['lowestPrice'];
    new MetaBox_Factory (
        array(
            "post_types" => array('destination'),
           "is_single_meta" => true,
            "meta_box"   => array(
                "id"        => "",
                "title"     => "lowestPrice",
                "position"  => "normal",
                "priority"  => "high",
            ),
            "fields"     => create_text_field_from_titles($titles,true)
            )
);   
/* TO DO SEO DATA TO BE ADDED to YOAD VIA hooks 
"seoData": {
    "seoTitle": 
    "seoDescription": 
    "seoKeywords": 
    "seoFooter": ""
}
*/



