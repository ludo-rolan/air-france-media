<?php

// metaboxes for bloc address_infos
for ($i = 1; $i <= 10; $i++) {
    new MetaBox_Factory(
        array(
            "post_types" => array('post', 'adresse'),
            "meta_box"   => array(
                "id"        => "carte_addr_" . $i,
                "title"     => "CARTE ADDRESS $i (shortcode : [carte_addr_$i])",
                "position"  => "normal",
                "priority"  => "high",
            ),
            "fields"     => array(
                array(
                    "label"     => "Titre",
                    "suffix_id" => "title_carte",
                    "sanitize"  => true,
                    "type"      => Text_MetaBox_Type::class,
                ),
                array(
                    "label"     => "Address de La Carte",
                    "suffix_id" => "adr_carte",
                    "args" => ["wpautop" => false],
                    "type"      => WPEditor_MetaBox_Type::class,
                )
            )
        )
    );
}
