<?php
global $site_config;
get_header('home');
//echo do_shortcode("[dfp id='habillage']");
$is_ipad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
if (!$site_config['ismobile'] && !$is_ipad) {
	get_header();
}
$term = get_queried_object();
$paged = get_query_var('paged') ?: 1;
$post_id_mea = get_option("category_$term->term_id");

$args = array(
	'posts_per_page' => 12,
	'cat' => $term->term_id,
	'paged' => $paged,
	'post__not_in' => [$post_id_mea]
);

?>
 <div id="megabanner_top">
            <?php echo do_shortcode("[dfp id='masthead_haut']") ?>
</div>
<?php

echo do_shortcode("[dfp id='mobile_1']") ;

get_breadcrumb();




if ($term->category_parent > 0) {
	$args['category_name'] = $term->slug;
}
$cache_key = $site_config['afmm_cache']['hp-category_posts']['key']. $term->slug . '_' . $paged;
$cache_time = $site_config['afmm_cache']['hp-category_posts']['time'];

$query = get_data_from_cache($cache_key, 'hp_rubrique', $cache_time, function () use ($args) {
	return new WP_Query($args);
});

if ($term->category_parent > 0) {
	include(locate_template('template-parts/block_post_a_la_une.php'));

} else {
	if ($post_id_mea) { ?>
        <div id="content" class="container">
        <div class="d-flex flex-wrap">
		<?php
		if (!empty($post_id_mea)) {
			include(locate_template("template-parts/block_post_mea.php"));
		}
	} ?>
    </div>
    </div>
<?php } ?>


<?php echo do_shortcode("[dfp id='native']") ?>
<?php echo do_shortcode("[dfp id='native_mobile']") ?>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="row mt-5">
					<?php
					if ($query->have_posts()) {
						while ($query->have_posts()) {
							$query->the_post();
							$link            = get_permalink();
							$category_detail = get_the_category(get_the_ID());
							?>
                            <div class="col-md-6 post">
								<?php include STYLESHEET_DIR . '/template-parts/home/post_preview_elem.php'; ?>
                            </div>
							<?php
						}
					}
					?>
                </div>
                <div class="mt-5">
					<?php do_action('afmm_pagination', $query, $paged); ?>
                </div>
            </div>
            <div class="sub-category_most_visited col-md-4 mt-5">
				<?php //if ($term->category_parent == 0) {
					echo do_shortcode("[dfp id='mpu_haut']") ;
				//} ?>

				<?php echo do_shortcode('[article_plus_lus]'); ?>
            </div>
        </div>
    </div>
	<?php   //if (!get_omep_val('couper_position_mobile_102_0741')) {
	echo do_shortcode("[dfp id='mobile_2']");
	//}
?>
<?php get_footer(); ?>