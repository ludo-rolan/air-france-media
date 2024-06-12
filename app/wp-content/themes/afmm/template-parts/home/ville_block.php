<?php
global $site_config;
global $option_hp;
$cache_key = $site_config['afmm_cache']['hp_villes']['key'];
$cache_time = $site_config['afmm_cache']['hp_villes']['time'];
$key_option = apply_filters('get_fields_deps_lang','hp_options_cinq_ville_tg');
$args = [];
$args['ids'] = $option_hp[$key_option]??[];
$ville_json  = get_data_from_cache($cache_key, 'hp_locking_ids', $cache_time, function () use ($args) {
   return Maillage::afmm_maillage_villes($args['ids']);
});
if (count($ville_json)) {
?>

<section id="hp-podcast-carousel" class="container-fluid podcast"
         style="background: #283583 url(<?php echo $site_config['carousel_pattern'] ?>) ">
    <div class="row">
        <h3 class="ml-3 podcast-mobile__head d-lg-none"><?php echo __('à visiter', AFMM_TERMS) ?></h3>
        <div class="owl-carousel podcast-carousel">
			<?php
				foreach ($ville_json as $ville) {
                    $thumbnail=$ville['_embedded']['wp:featuredmedia']['0']['source_url']  ;
                    $link  =  $ville['link'];  
					?>
                    <div class="card podcast-carousel-card__container">
                        <div class="podcast-carousel-article__head">
                        <a href="<?php  echo $ville['link']  ?>">
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
                            <span class="podcast-carousel-article__cat"> <?php _e("DESTINATIONS",AFMM_TERMS) ?></span>
                            <h3 class="podcast-carousel-article__title"><?php echo $ville['title']['rendered'] ?></h3>
                            <!-- <a class="btn af-btn--podcast"
                               href="<?php // the_permalink(); ?>"><?php // echo __( 'Écouter le podcast', AFMM_TERMS ) ?></a> -->
                            <a class="btn af-btn--podcast"
                            href="<?php echo $ville['link'] ?>"><?php echo __( 'Découvrir la ville', AFMM_TERMS) ?></a>
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
        
    </div>
</section>

<?php do_action('after_hp_podcast_carousel') ?>