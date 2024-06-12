<?php

// Metabox Avion

new MetaBox_Factory (
    array(
        "post_types" => array('avion'),
        "meta_box"   => array(
            "id"        => "donnees_avion",
            "title"     => "Données Avion",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Numéro D'Avion",
                "suffix_id" => "_vol_num",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Siège",
                "suffix_id" => "_siege",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Vitesse",
                "suffix_id" => "_vitesse",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Envergure",
                "suffix_id" => "_envergure",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Longueur",
                "suffix_id" => "_longueur",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Type Moteur",
                "suffix_id" => "_typeMoteur",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Lien de Recherche D'un Vol",
                "suffix_id" => "_Lien_recher_un_vol",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Titre Secondaire",
                "suffix_id" => "_titre_secondaire",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "L'image de la Cabine",
                "suffix_id" => "_cabine_image",
                "sanitize"  => false,
                "type"      => Single_image_MetaBox_Type::class,
            ),
            array(
                "label"     => "Gallery",
                "suffix_id" => "_avion_gallery",
                "sanitize"  => false,
                "type"      => Gallery_MetaBox_Type::class,
            )

        )
    )
);
