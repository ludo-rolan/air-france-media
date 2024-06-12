<?php
function dynamic_filters_content($args, $images = null) {
	$filters_content = [];
	$filters_terms = get_terms( $args );
	$imgs = isset($images) && is_array($images) ? $images : [];
	foreach ( $filters_terms as $key => $term ) {
		$filters_content[] = [$term->name, $term->slug, $imgs[$key] ?? null, $term->term_id];
	}
	return $filters_content;
}