<?php
global $site_config;
$envies = array();
$dists = array();

$folder_dist = AF_THEME_DIR .'/assets/img/af-biblio/DESTINATION/*.svg';
$folder_envies = AF_THEME_DIR .'/assets/img/af-biblio/ENVIES/*.svg';
//get liste of files abs 
$files_dist = glob($folder_dist);
$files_envies = glob($folder_envies);

//fill arrays with the svgs names in the folders envies and destination
foreach ( $files_dist as $file ) {
    if ( is_file( $file ) ) {
        $filename = basename( $file );  
        array_push($dists, $filename);
    }
}
foreach ( $files_envies as $file ) {
    if ( is_file( $file ) ) {
        $filename = basename( $file );  
        array_push($envies, $filename);
    }
}


new MetaBox_Factory (
    array(
        "post_types" => $site_config['meta_visiter_endroit_post_type'],
        "meta_box"   => array(
            "id"        => "visitez_endroit",
            "title"     => "Block visitez endroit",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Titre",
                "suffix_id" => "_titre",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Description",
                "suffix_id" => "_discription",
                "type"      => WPEditor_MetaBox_Type::class,
            ),
            array(
                "label"     => "Vignette 1",
                "suffix_id" => "_vignette1",
                "type"      => Checkbox_MetaBox_Type::class,
            ),
            array(
                "label"     => "Titre de la Vignette 1",
                "suffix_id" => "_titre_vignette1",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Lien 1",
                "suffix_id" => "_lien_vignette1",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "VIGNETTE DESTINATION ICONE: ",
                "suffix_id" => "_svg_vig1",
                "sanitize" => false,
                "type"      => SingleIMG_Select_MetaBox_Type::class,
                "args"      => array(
                    "options"       => $dists, "multiselect"   => false, "category" => "DESTINATION"
                )
            ),
            array(
                "label"     => "Couleur de fond de la Vignette 1",
                "suffix_id" => "_bg_color_vignette1",
                "type"      => ColorWheel_MetaBox_Type::class,
            ),
            array(
                "label"     => "Vignette 2",
                "suffix_id" => "_vignette2",
                "type"      => Checkbox_MetaBox_Type::class,
            ),
            array(
                "label"     => "Titre de la Vignette 2",
                "suffix_id" => "_titre_vignette2",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Lien 2",
                "suffix_id" => "_lien_vignette2",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                
                "label"     => "VIGNETTE ENVIES ICONE: ",
                "suffix_id" => "_svg_vig2",
                "sanitize" => false,
                "type"      => SingleIMG_Select_MetaBox_Type::class,
                "args"      => array(
                    "options"      => $envies, "multiselect"   => false, "category" => "ENVIES"
                )
            ),
            array(
                "label"     => "Couleur de fond de la Vignette 2",
                "suffix_id" => "_bg_color_vignette2",
                "type"      => ColorWheel_MetaBox_Type::class,
            )
            ,array(
                "label" => "personnaliser le lien",
                "suffix_id" => "_custom_link",
                "type" => Checkbox_MetaBox_Type::class,
            ),
            array(
                "label"     => "Lien de la Vignette 3",
                "suffix_id" => "_lien_vignette3",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            )
        )
    )
);