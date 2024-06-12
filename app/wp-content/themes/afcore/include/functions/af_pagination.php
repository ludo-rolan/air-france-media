<?php

class af_pagination {

	public static function show_pagination( $query, $paged) {
		global $site_config;
		$big           = 999999999;
		$is_mobile = $site_config['ismobile'];
		$mid_size = $is_mobile ? 2 : 5;
		$total_pages = $is_mobile ? 4 : 10;
		// in first pages before arriving to the middle, and in the last pages
		if($paged <= $mid_size) $mid_size = $total_pages - $paged;
		elseif (intval($query->max_num_pages) - $paged <= $mid_size) $mid_size = $total_pages + $paged - intval($query->max_num_pages);
		$paginate_args = array(
			'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'show_all'  => false,
			'end_size'  => 1,
			'mid_size'  => $mid_size,
			'prev_text' => '<img src="' . $site_config['pagination']['left'] . '">',
			'next_text' => '<img src="' . $site_config['pagination']['right'] . '">',
			'total'     => $query->max_num_pages,
			'type'      => 'array'
		);

		$paginate_links = paginate_links( $paginate_args );

		if ( ! $paginate_links ) {
			return false;
		}

		$r = '';

		foreach ( $paginate_links as $link ) {
			$is_active = strpos( $link, 'page-numbers current' ) !== false;
			$is_prev   = strpos( $link, 'prev page-numbers' ) !== false;
			$is_next   = strpos( $link, 'next page-numbers' ) !== false;
			$r         .= '<li class="' . ( $is_active ? 'active' : '' ) . ( $is_prev ? 'prev-page' : '' ) . ( $is_next ? 'next-page' : '' ) . '">' . $link . '</li>';
		}
		$prev_btn = '';
		$next_btn = '';
		if ( $paged == 1 ) {
			$prev_btn = '<li><a href="javascript:void(0)" class="disabled"><img src="' . $site_config['pagination']['left'] . '"></a></li>';
		}

		if ( $paged == $query->max_num_pages ) {
			$next_btn = '<li><a href="javascript:void(0)" class="disabled"><img src="' . $site_config['pagination']['right'] . '"></a></li>';
		}

		if ( ! empty( $r ) ) {
			$r = '<ul class="pagination">' . $prev_btn . $r . $next_btn . '</ul>';
		}

		return $r;
	}
}