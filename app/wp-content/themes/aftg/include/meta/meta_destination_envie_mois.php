<?php

$taxs = ['envies', 'mois'];

foreach ($taxs as $tax) {
    $terms = get_terms([
        'taxonomy' => $tax,
        'hide_empty' => false
    ]);
    $fields=[] ;
    foreach($terms as $term){
        $fields[] = ["id"=>$term->term_id,"title"=>$term->name];
    }
    $fields = create_number_input_from_titles($fields);

    new MetaBox_Factory(
        array(
            "post_types" => array('destination'),
            "meta_box"   => array(
                "id"        => $tax,
                "title"     => "les  critéres de classement par  " . $tax,
                "position"  => "side",
                "priority"  => "default",
            ),
            "fields"     => $fields,
        )
    );
}


