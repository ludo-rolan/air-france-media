<?php 

// Metabox for code IATA

new MetaBox_Factory (
    array(
        "post_types" => array('post', 'travel-guide'),
        "meta_box"   => array(
            "id"        => "iata",
            "title"     => "IATA",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Code IATA",
                "suffix_id" => "_code_iata",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Devise",
                "suffix_id" => "_devise",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Fuseau horaire",
                "suffix_id" => "_fuseau_horaire",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Langue",
                "suffix_id" => "_langue",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Bloc billéterie",
                "suffix_id" => "_bloc_billeterie",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            )
        )
    )
);