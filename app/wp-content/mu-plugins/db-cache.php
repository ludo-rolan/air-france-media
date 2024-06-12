<?php

// FORCE CACHE ON SQL_CALC_FOUND_ROWS QUERIES
function rw_force_cache_queries($query , $wp_query = null){
	return preg_replace ( '/SELECT /' , 'SELECT SQL_CACHE ', $query, 1) ;

}


add_filter ( 'posts_request' , 'rw_force_cache_queries' , 100 , 2);
add_filter ( 'posts_request_ids' , 'rw_force_cache_queries' , 100 , 2);
add_filter ( 'rw_force_cache_queries' , 'rw_force_cache_queries' , 100 , 1);