<?php
global $site_config;
new MetaBox_Factory (
    array(
        "post_types" => array('post'),
        "is_single_meta" =>true,
        "meta_box"   => array(
            "id"        => "post_display_type",
            "title"     => "Type De l'article",
            "position"  => "side",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Type Du Post",
                "suffix_id" => "_name",
                "type"      => RadioButtons_MetaBox_Type::class,
                "args"      => array(
                    "options" => $site_config['meta_post_type_options'],
                    "default"  => 'LONGFORM',
                    "inline"    => true
                )
            ),
            
        )
    )
);


new MetaBox_Factory (
    array(
        "post_types" => array('post'),
        "meta_box"   => array(
            "id"        => "monetisation",
            "title"     => "Monétisation ",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Monétisation galerie",
                "suffix_id" => "_diapo_gallery",
                "sanitize"  => false,
                "type"      => Gallery_MetaBox_Type::class
            ),
            array(
                "label"     => "légende galerie",
                "suffix_id" => "_légende",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),

        )
    )
);
new MetaBox_Factory (
    array(
        "post_types" => array('post'),
        "is_single_meta" =>true,
        "meta_box"   => array(
            "id"        => "chaud_froid",
            "title"     => "Chaud ou Froid",
            "position"  => "side",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Type Du Post",
                "suffix_id" => "_type",
                "type"      => RadioButtons_MetaBox_Type::class,
                "args"      => array(
                    "options" => array('CHAUD','FROID'),
                    "default"  => 'FROID',
                    "inline"    => true
                )
            ),
            
        )
    )
);