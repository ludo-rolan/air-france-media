<?php
global $site_config,$wpdb;
get_header();

$paged = get_query_var( 'paged' ) ?: 1;
$search_query = get_search_query();
$cache_key = $site_config['afmm_cache']['search']['key']."_".$search_query."_".$paged;
$cache_time = $site_config['afmm_cache']['search']['time'];
$query  = get_data_from_cache($cache_key, 'hp_locking_ids', $cache_time, function () use ($search_query,$paged,$wpdb,$site_config) {
$sql="SELECT SQL_CACHE wp_posts.ID  FROM wp_posts LEFT JOIN wp_icl_translations wpml_translations ON wp_posts.ID = wpml_translations.element_id AND wpml_translations.element_type = CONCAT('post_', wp_posts.post_type) WHERE 1=1 AND (((wp_posts.post_title LIKE '% ".$search_query." %') OR (wp_posts.post_excerpt LIKE '% ".$search_query." %') OR (wp_posts.post_content LIKE '% ".$search_query."%'))) AND (wp_posts.post_password = '') AND wp_posts.post_type IN ('post', 'travel-guide') AND (wp_posts.post_status = 'publish') AND ( ( ( wpml_translations.language_code = '".$site_config["langue_selectionner"]."' OR 0 ) AND wp_posts.post_type IN ('post','page','attachment','wp_block','wp_template','travel-guide' ) ) )ORDER BY wp_posts.post_title LIKE '% ".$search_query." %' DESC, wp_posts.post_date DESC";
if ( get_omep_val('cacher_article_type_diapo_acquisition_0811')) {
    $sql="SELECT SQL_CACHE wp_posts.ID  FROM wp_posts LEFT JOIN wp_icl_translations wpml_translations ON wp_posts.ID = wpml_translations.element_id AND wpml_translations.element_type = CONCAT('post_', wp_posts.post_type) INNER JOIN wp_postmeta wp_meta ON wp_meta.post_id = wp_posts.ID WHERE 1=1 AND (wp_meta.meta_key = 'post_display_type_name' AND wp_meta.meta_value NOT LIKE 'DIAPO ACQUISITION') AND (((wp_posts.post_title LIKE '% ".$search_query." %') OR (wp_posts.post_excerpt LIKE '% ".$search_query." %') OR (wp_posts.post_content LIKE '% ".$search_query."%'))) AND (wp_posts.post_password = '') AND wp_posts.post_type IN ('post', 'travel-guide') AND (wp_posts.post_status = 'publish') AND ( ( ( wpml_translations.language_code = '".$site_config["langue_selectionner"]."' OR 0 ) AND wp_posts.post_type IN ('post','page','attachment','wp_block','wp_template','travel-guide' ) ) )ORDER BY wp_posts.post_title LIKE '% ".$search_query." %' DESC, wp_posts.post_date DESC";
}
$values = $wpdb->get_col( $sql );
$args = array(
	"post__in"=> (!isset($values) || empty($values)) ? array(-1):$values,
	'posts_per_page' => 12,
    'paged' => $paged,
	"post_type"=>array('post','travel-guide')
);
return  new WP_Query( $args );

});


?>
<div class="container">

 <div id="megabanner_top">
<?php

echo do_shortcode("[dfp id='masthead_haut']") ;

echo do_shortcode("[dfp id='mobile_1']") ;

?>
</div>
	<?php get_breadcrumb(); ?>
</div>
</div>
<section class="container">
<div class="rechercher-resultat-title pl-lg-3 pl-md-5">
	<span>
		<?php 
		echo $query->found_posts . ' ' 
		.(($query->found_posts != 1) ? __('Résultats pour', AFMM_TERMS) : __('Résultat pour', AFMM_TERMS))
		.' "' . $search_query . '"';
		?>
	</span>
</div>
</div>

<div class="row">
	<div class="col-lg-8 mb-4">
		<div class="row justify-content-around mb-4">
				<?php
				if ($query->have_posts()) {
					$i = 0 ;
					while ($query->have_posts()) {
						$i ++ ;
						$query->the_post();
						?>
						<div class="col-md-6 post rechercher-resultat-post">
							<?php include STYLESHEET_DIR . '/template-parts/home/post_preview_elem.php'; 

							?>
						</div>
						<?php 
						do_action('after_post_searsh', $i );

					}
				}
				?>
		</div>
		<?php do_action('afmm_pagination', $query, $paged); ?>
	</div>
	<div class="col-lg-4 mb-4">
	

		<div class="mb-4">
		<?php echo do_shortcode("[dfp id='mpu_haut']"); ?>
		</div>
		<div class="mb-3">
			<?php echo do_shortcode("[dfp id='mpu_milieu']"); ?>
		</div>
	</div>
</div>
</section>
<?php get_footer();