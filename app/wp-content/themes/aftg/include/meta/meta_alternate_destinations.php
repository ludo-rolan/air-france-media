<?php
$args = array('post_type' => "destination", 'posts_per_page' => -1);
$fields = [];
foreach (get_posts($args) as $post) {
    $fields[] = ["value" => $post->ID, "label" => $post->post_name];
}
new MetaBox_Factory(
    array(
        "post_types" => array('destination'),
        "is_single_meta"=>true,
        "meta_box"   => array(
            "id"        => "alternateDestinations",
            "title"     => "alternate Destinations",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Destinations",
                "suffix_id" => "_ids",
                "sanitize" => false,
                "type"      => Select_MetaBox_Type::class,
                "args"      => array(
                    "options"       => $fields, "multiselect"   => true, "is_options_array" => true
                )
            ),
        )
    )
);
