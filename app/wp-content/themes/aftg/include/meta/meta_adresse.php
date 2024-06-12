<?php 

//TO DO : content paragraph mainpicture introduction small publicationdate


$texts=['type','id','articleUrl','city','country','region','topic'];

// commences avec is
$checkboxs=['Hidden','Partner','Crush','MultiAddress'];

new MetaBox_Factory (
	array(
		"post_types" => array('adresse'),
		"is_single_meta" => true,
		"meta_box"   => array(
			"id"        => "wemap_poi",
			"title"     => "Synchroniser wemap POI",
			"position"  => "side",
			"priority"  => "high",
		),
		"fields"     => create_text_field_from_titles(['Synchroniser']),
	)
);

new MetaBox_Factory (
	array(
		"post_types" => array('adresse','post'),
		"is_single_meta" => true,
		"meta_box"   => array(
			"id"        => "is",
			"title"     => "Types",
			"position"  => "normal",
			"priority"  => "high",
		),
		"fields"     => create_checkbox_from_titles($checkboxs,true)
	)
);

$localisation_title = ['map'];
new MetaBox_Factory (
	array(
		"post_types" => array('adresse'),
		"is_single_meta" => true,
		"meta_box"   => array(
			"id"        => "localisation",
			"title"     => "localisation",
			"position"  => "normal",
			"priority"  => "high",
		),
		"fields"     => create_text_field_from_titles($localisation_title),
	)
);

new MetaBox_Factory (
    array(
        "post_types" => array('adresse','post'),
        "is_single_meta" => true,
        "meta_box"   => array(
            "id"        => "",
            "title"     => "MetaData",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => create_text_field_from_titles($texts,true)
    )
);

new MetaBox_Factory (
	array(
		"post_types" => array('adresse'),
		"is_single_meta" => true,
		"meta_box"   => array(
			"id"        => "geo",
			"title"     => "GeoCordonees",
			"position"  => "normal",
			"priority"  => "high",
		),
		"fields"     => array(
			array(
				"label"     => "GeoCordonees",
				"suffix_id" => "_coordonees",
				"sanitize"  => true,
				"type"      => Text_MetaBox_Type::class,
			)
		)
	)
);

new MetaBox_Factory (
	array(
		"post_types" => array('adresse'),
		"is_single_meta" => true,
		"meta_box"   => array(
			"id"        => "partenaire",
			"title"     => "partenaire",
			"position"  => "normal",
			"priority"  => "high",
		),
		"fields"     => array(
			array(
				"label"     => "partenaire",
				"suffix_id" => "_partenaire",
				"sanitize"  => true,
				"type"      => Text_MetaBox_Type::class,
			)
		)
	)
);
