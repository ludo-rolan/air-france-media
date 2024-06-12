<?php
$args = array(
	'taxonomy'   => 'destinations',
	'hide_empty' => false,
	'parent'     => 0
);

$filters_imgs = [
	AF_THEME_DIR_URI . '/assets/img/af-biblio/DESTINATION/AFRIQUE.svg',
	AF_THEME_DIR_URI . '/assets/img/af-biblio/DESTINATION/AMERIQUE.svg',
	AF_THEME_DIR_URI . '/assets/img/af-biblio/DESTINATION/ASIE.svg',
	AF_THEME_DIR_URI . '/assets/img/af-biblio/DESTINATION/CARAIBES.svg',
	AF_THEME_DIR_URI . '/assets/img/af-biblio/DESTINATION/EUROPE.svg',
	AF_THEME_DIR_URI . '/assets/img/af-biblio/DESTINATION/FRANCE.svg',
];

Multi_Select_Filter::render_filters( dynamic_filters_content($args, $filters_imgs), 'select_region', $select_title, $is_content_clickable );
if ($is_modal_content) { ?>
<div class="d-flex justify-content-center mt-4">
    <span>
        <a class="multi_select_filter-btn mt-3"><?php _e( 'Valider' , AFMM_TERMS) ?></a>
    </span>
</div>
<?php } ?>
<div class="multi_select_filter_result">
    <?php if (!$is_modal_content) { ?>
        <h2 class="d-none"><?php _e( 'liste des destinations', AFMM_TERMS) ?></h2>
        <span class="d-none"></span>
        <div class="row col-md-12 mt-3 multi_select_filter_result_content d-none"></div>
    <?php } ?>
</div>