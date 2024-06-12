<?php 

// This is a metabox for Edisound podcast block to be integrated in the articles BackOffice,
// it contains a checkbox for activation, a placement id text field, a data pid text field and a data gid text field.

new MetaBox_Factory (
    array(
        "post_types" => array('post','travel-guide'),
        "meta_box"   => array(
            "id"        => "edisound",
            "title"     => "Edisound",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Activer Edisound",
                "suffix_id" => "_active",
                "sanitize"  => true,
                "type"      => Checkbox_MetaBox_Type::class,
            ),
            array(
                "label"     => "Placement ID",
                "suffix_id" => "_placement_id",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Data PID",
                "suffix_id" => "_data_pid",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Data GID",
                "suffix_id" => "_data_gid",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            )
        )
    )
);