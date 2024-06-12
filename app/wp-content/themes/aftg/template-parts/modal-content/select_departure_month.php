<?php
global $site_config;
$args = array(
	'taxonomy'   => 'mois',
	'hide_empty' => false,
	'parent'     => 0,
	'orderby'    => 'name',
);
$filters_imgs = [
	'01',
	'02',
	'03',
	'04',
	'05',
	'06',
	'07',
	'08',
	'09',
	'10',
	'11',
	'12',
];
$result = dynamic_filters_content($args);
$current_lang = apply_filters( 'wpml_current_language', NULL );

usort($result, function ($a, $b) {
	$monthA = date_parse($a[0]);
	$monthB = date_parse($b[0]);

	return $monthA["month"] - $monthB["month"];
});

$sorted_arr_with_img = [];
foreach ( $result as $key => $item ) {
    if ($current_lang === 'fr') {
	    $item[0] = $site_config['months_fr'][$key];
    }
	$item[2] = $filters_imgs[$key];
	$sorted_arr_with_img[] = $item;
}

Multi_Select_Filter::render_filters( $sorted_arr_with_img, 'select_departure',  'Sélectionnez le(s) mois de départ');
?>
<div class="d-flex justify-content-center mt-4">
    <span>
        <a class="multi_select_filter-btn mt-3"><?php _e( 'Valider', AFMM_TERMS) ?></a>
    </span>
</div>