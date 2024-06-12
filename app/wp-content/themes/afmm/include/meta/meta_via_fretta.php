<?php

// Metabox for bloc VIA FRETTA
$texts = ['titre', 'adresse', 'ville', 'pays', 'latitude', 'longitude', 'altitude', 'aeroport_plus_proche'];
for ($i = 1; $i <= 10; $i++) {
    new MetaBox_Factory(
        array(
            "post_types" => array('post', 'travel-guide'),
            "meta_box"   => array(
                "id"        => "via_fretta_" . $i,
                "title"     => "PINNED ADDRESS $i (shortcode : [pinned_address_$i])",
                "position"  => "normal",
                "priority"  => "high",
            ),
            "fields"     => create_text_field_from_titles($texts, true)
        )
    );
}
