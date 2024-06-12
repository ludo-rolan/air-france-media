<?php 

new MetaBox_Factory (
    array(
        "post_types" => array(apply_filters('resize_thumb_post_type', '')),
        "is_single_meta" =>true,
        "meta_box"   => array(
            "id"        => "resize_thumb",
            "title"     => "Image mobile",
            "position"  => "side",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Portrait",
                "suffix_id" => "_resize",
                "sanitize"  => true,
                "type"      => Checkbox_MetaBox_Type::class,
            ),
            
        )
    )
);