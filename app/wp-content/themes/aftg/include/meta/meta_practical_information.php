<?php

// TODO : use this list to get the languagues meta 
//$nex_language=["en","fr","de","es","it", "ja", "pt" ,"zh"];
$language = ["en", "fr"];

new MetaBox_Factory(
    array(
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "essentialPhrases",
            "title"     => "essentialPhrases (practical information)",
            "position"  => "normal",
            "priority"  => "low",
        ),
        "fields"     => [
            array(
                "label"     => "content en",
                "suffix_id" => "_en",
                "sanitize"  => false,
                "type"      => WPEditor_MetaBox_Type::class,
            ),
            array(
                "label"     => "content fr",
                "suffix_id" => "_fr",
                "sanitize"  => false,
                "args" => ["wpautop" => false],
                "type"      => WPEditor_MetaBox_Type::class,
            ),
        ]
    )
);



// practical_information
$titles_descriptions = [];
$titles_descriptions['localCalendar'] = ['title', 'description'];
$titles_descriptions['airports'] = ['title', 'description'];
$titles_descriptions['transportation'] = ['title', 'description'];
$titles_descriptions['touristInformation'] = ['title', 'description'];
$titles_descriptions['medical'] = ['title', 'description'];
$titles_descriptions['administrativeProcedures'] = ['title', 'description'];
$titles_descriptions['usefulAddresses'] = ['title', 'description'];
$titles_descriptions_args = ['wpautop' => false];
foreach ($titles_descriptions as $key => $group_titles) {
    new MetaBox_Factory(
        array(
            "post_types" => array('destination'),
            "meta_box"   => array(
                "id"        => $key,
                "title"     => $key . " (practical information)",
                "position"  => "normal",
                "priority"  => "low",
            ),
            "fields"     => create_title_description_from_titles($group_titles, null, $titles_descriptions_args)
        )
    );
}
$code_editor_meta = ['goodToKnow', 'monthInformation'];
foreach ($code_editor_meta as $elem) {
    $metabox_args = array(
        "is_single_meta"=>true,
        "post_types" => array('destination'),
        "meta_box"   => array(
            "id"        => "$elem",
            "title"     => "$elem (practical information)",
            "position"  => "normal",
            "priority"  => "low",
        ),
        "fields"     => array(
            array(
                "label"     => "Content",
                "suffix_id" => "_content",
                "sanitize"  => false,
                "type"      => CodeEditor_MetaBox_Type::class,
            ),
        )

    );
    
    if($elem === 'goodToKnow') {
        $metabox_args['fields'][0]['args'] = array(
            "default" =>'{
                "title": "",
                "administration": {
                    "title": "",
                    "openingHours": ""
                },
                "banks": {
                    "title": "",
                    "openingHours": ""
                },
                "localIndicative": {
                    "number": "",
                    "label": ""
                },
                "daylightSavingTime": "",
                "plugTypes": [],
                "voltage": "",
                "tips": ""
            }'
        );
    }

    new MetaBox_Factory($metabox_args);
}


$titles = [];
//[TR-0600] Optimisation du process d'intégration et modifications des destinations, POI */
//$titles['DestinationWeather'] = ['title', 'introduction', 'temperatureValue', 'pictogramUrl'];
$titles['DestinationWeather'] = [ 'title','introduction',];
$titles['destinationSpokenLanguages'] = ['code', 'label'];
$titles['titles'] = [
    'administrativeProcedures','localCalendar','medical', 'goodToKnow', 'weather', 'currency', 'essentialPhrases',
    'touristInformation', 'airports', 'transportation','usefulAddresses'
];
$titles['currency'] = ['label', 'symbol',];
$titles['time'] = [ 'timeZone', 'effectiveFlightDuration'];
foreach ($titles as $key => $group_titles) {
    new MetaBox_Factory(
        array(
            "post_types" => array('destination'),
            "meta_box"   => array(
                "id"        => $key,
                "title"     => $key,
                "position"  => "normal",
                "priority"  => "default",
            ),
            "fields"     => create_text_field_from_titles($group_titles)
        )
    );
}
