<?php
global $site_config, $option_hp, $posts_exclude;
$cache_key = $site_config['afmm_cache']['hp_video']['key'];
$cache_time = $site_config['afmm_cache']['hp_video']['time'];
$key_option = apply_filters('get_fields_deps_lang','hp_options_cinq_post_video');
$hp_show_category_posts_args = ['ids' => $option_hp[ $key_option ], 'max_posts_number' => 5];
$args = apply_filters('hp_show_category_posts', $hp_show_category_posts_args, 'video');
$query  = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function() use ($args){
    return new WP_Query( $args );
});
if (isset($query->posts[0]) && !empty($query->posts[0]->ID)) {
	$posts_exclude[] = $query->posts[0]->ID;
}
if ( $query->have_posts() ) {
	?>
    <section id="hp-video-carousel" class="container-fluid video"
             style="background: #51bba7 url(<?php echo $site_config['carousel_pattern'] ?>) ">
        <div class="row">
            <h3 class="ml-3 video-mobile__head d-lg-none"><?php echo __( 'vidéos', AFMM_TERMS) ?></h3>
            <div class="owl-carousel video-carousel">
				<?php
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$thumbnail       = get_the_post_thumbnail_url( get_the_ID(), 'full' );
						$category_detail = get_the_category( get_the_ID() );
						?>
                        <div class="card video-carousel-card__container">
                            <div class="video-carousel-article__head">
                            <a href="<?php echo the_permalink(); ?>">
                                <div class="video-carousel-article__img"
                                     style="background-image: url('<?php echo $thumbnail ?>')">
                                    <div class="video-carousel-article__play-icon">
                                        <img class="video-carousel-article__play-icon__img" alt=""
                                             src="<?php echo $site_config['play_icon'] ?>">
                                    </div>
                                </div>
                            </a>
                            </div>
                            <div class="card-body">
                                <span class="video-carousel-article__cat"><?php echo Af_Category_Helper::get_post_categorie_name( $category_detail ); ?></span>
                                <h3 class="video-carousel-article__title"><?php echo get_the_title() ?></h3>
                                <a class="btn af-btn--video"
                                   href="<?php the_permalink(); ?>"><?php echo __( 'Lire la vidéo', AFMM_TERMS ) ?></a>
                                <div class="af__owl-nav">
                            <span class="owl-prev--video">
                                <img alt="" src="<?php echo $site_config['hp_carousel_nav_left'] ?>">
                            </span>
                                    <span class="owl-next--video">
                                <img alt="" src="<?php echo $site_config['hp_carousel_nav_right'] ?>">
                            </span>
                                </div>
                                <div class="video-progress-container">
                                    <span></span>
                                    <div class="progress">
                                        <div id="video-dynamic" class="progress-bar" role="progressbar"
                                             aria-valuenow="0"
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span></span>
                                </div>
                            </div>
                        </div>
						<?php
					}
				} ?>
            </div>
            <div class="col col-md-6 mx-auto text-center">
                <a class="btn af-btn--video--mobile d-lg-none"
                   href="<?php the_permalink(); ?>"><?php echo __( 'Voir plus de vidéos', AFMM_TERMS) ?></a>
            </div>
        </div>
    </section>
<?php } 
    do_action('after_hp_video_carouse');

?>

