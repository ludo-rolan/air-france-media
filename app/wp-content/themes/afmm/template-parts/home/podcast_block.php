<?php
global $site_config;
global $option_hp;
$cache_key = $site_config['afmm_cache']['hp_podcast']['key'];
$cache_time = $site_config['afmm_cache']['hp_podcast']['time'];
$key_option = apply_filters('get_fields_deps_lang','hp_options_cinq_post_podcast');
$hp_show_category_posts_args = ['ids' => $option_hp[$key_option], 'max_posts_number' => 5];
$args = apply_filters('hp_show_category_posts', $hp_show_category_posts_args, 'podcast');
$query  = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function() use ($args){
    return new WP_Query( $args );
});
if ( $query->have_posts() ) {
?>
<section id="hp-podcast-carousel" class="container-fluid podcast"
         style="background: #283583 url(<?php echo $site_config['carousel_pattern'] ?>) ">
    <div class="row">
        <h3 class="ml-3 podcast-mobile__head d-lg-none"><?php echo __('podcast', AFMM_TERMS) ?></h3>
        <div class="owl-carousel podcast-carousel">
			<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$thumbnail       = get_the_post_thumbnail_url( get_the_ID(), 'full' );
					$category_detail = get_the_category( get_the_ID() );
					?>
                    <div class="card podcast-carousel-card__container">
                        <div class="podcast-carousel-article__head">
                        <a href="<?php echo the_permalink(); ?>">
                            <div class="podcast-carousel-article__img"
                                 style="background-image: url('<?php echo $thumbnail ?>')">
                                <!-- <div class="podcast-carousel-article__microphone">
                                    <img class="podcast-carousel-article__microphone__img" alt=""
                                         src="<?php //echo $site_config['microphone_img'] ?>">
                                </div> -->
                            </div>
                        </a>
                        </div>
                        <div class="card-body">
                            <span class="podcast-carousel-article__cat"><?php echo Af_Category_Helper::get_post_categorie_name( $category_detail ); ?></span>
                            <h3 class="podcast-carousel-article__title"><?php echo get_the_title() ?></h3>
                            <!-- <a class="btn af-btn--podcast"
                               href="<?php // the_permalink(); ?>"><?php // echo __( 'Écouter le podcast' , AFMM_TERMS) ?></a> -->
                            <a class="btn af-btn--podcast"
                            href="<?php the_permalink(); ?>"><?php echo __( 'Découvrir la ville', AFMM_TERMS ) ?></a>
                            <div class="af__owl-nav">
                            <span class="owl-prev--podcast">
                                <img alt="" src="<?php echo $site_config['hp_carousel_nav_left'] ?>">
                            </span>
                                <span class="owl-next--podcast">
                                <img alt="" src="<?php echo $site_config['hp_carousel_nav_right'] ?>">
                            </span>
                            </div>
                            <div class="progress-container">
                                <span></span>
                                <div class="progress">
                                    <div id="podcast-dynamic" class="progress-bar" role="progressbar" aria-valuenow="0"
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
            <a class="btn af-btn--podcast--mobile d-lg-none"
               href="<?php the_permalink(); ?>"><?php echo __( 'Écouter plus de podcasts', AFMM_TERMS) ?></a>
        </div>
    </div>
</section>