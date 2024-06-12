<?php
$args = array(
	'taxonomy'   => 'envies',
	'hide_empty' => true,
	'parent'     => 0,
	'orderby'    => 'name',
);
$filters_imgs = [
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/plage.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/citybreak.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/culture.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/famille.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/gastronomy.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/golf.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/mountain.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/nature.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/nightlife.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/roadtrip.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/romance.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/sea.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/shopping.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/sun.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/spa.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/ballon.svg' ,
];

$filters_imgs_en = [
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/plage.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/citybreak.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/culture.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/famille.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/gastronomy.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/golf.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/mountain.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/nature.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/nightlife.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/roadtrip.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/romance.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/sea.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/shopping.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/sun.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/ballon.svg' ,
	AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/spa.svg' ,
];

$filters_imgs = ($site_config["langue_selectionner"] == "en") ? $filters_imgs_en : $filters_imgs;
$result = dynamic_filters_content($args, $filters_imgs);
$current_lang = apply_filters( 'wpml_current_language', NULL );

$desires_arr = [];
if ($current_lang === 'fr') {
	foreach ( $result as $key => $item ) {
	    $item[0] = $site_config['desires_fr'][$key];
		$desires_arr[] = $item;
    }
}

Multi_Select_Filter::render_filters( $current_lang === 'fr' ? $desires_arr : $result, 'select_desire', 'Sélectionnez une ou plusieurs envies');
?>
<div class="d-flex justify-content-center mt-4">
    <span>
        <a class="multi_select_filter-btn mt-3"><?php _e( 'Valider', AFMM_TERMS) ?></a>
    </span>
</div>