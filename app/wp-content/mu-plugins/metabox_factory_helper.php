<?php

function create_text_field_from_titles($titles, $no_underscore = false)
{
    $fields = [];
    foreach ($titles as $title) {
        $id = ($no_underscore)?$title:"_" . $title;
        $fields[] = array(
            "label"     => $title,
            "suffix_id" => $id,
            "sanitize"  => true,
            "type"      => Text_MetaBox_Type::class,
        );
    }
    return $fields;
}
function create_checkbox_from_titles($titles, $no_underscore = false)
{
    $fields = [];
    foreach ($titles as $title) {
	    $id = ($no_underscore)?$title:"_" . $title;
	    $fields[] = array(
            "label"     => $title,
            "suffix_id" => $id,
            "sanitize"  => true,
            "type"      => Checkbox_MetaBox_Type::class,
        );
    }
    return $fields;
}

function create_number_input_from_titles($titles, $no_underscore = false)
{
    $fields = [];
    foreach ($titles as $elem) {
        $id = ($no_underscore)?$elem['id']:"_" . $elem['id'];
        $id = "_" . $elem['id'];
        $fields[] = array(
            "label"     => $elem['title'],
            "suffix_id" => $id,
            "sanitize"  => true,
            "type"      => Number_MetaBox_Type::class,
        );
    }
    return $fields;
}
function create_title_description_from_titles($titles, $no_underscore = false, $args = [])
{
    $fields = [];
    foreach ($titles as $key=> $title) {
        $id = ($no_underscore)?$title:"_" . $title;
        $fields[] = array(
            "label"     => $title,
            "suffix_id" => $id,
            "sanitize"  => false,
            "args" => $args,
            "type"      => $key==0 ?Text_MetaBox_Type::class : WPEditor_MetaBox_Type::class,
        );
    }
    return $fields;
}
