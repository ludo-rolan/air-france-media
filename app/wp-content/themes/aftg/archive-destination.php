<?php

get_header();
global $site_config;
const envies_imgs = [
	'beach' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/plage.svg' ,
	'citybreak' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/citybreak.svg' ,
	'culture' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/culture.svg' ,
	'family' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/famille.svg' ,
	'gastronomy' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/gastronomy.svg' ,
	'golf' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/golf.svg' ,
	'mountain' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/mountain.svg' ,
	'nature' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/nature.svg' ,
	'nightlife' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/nightlife.svg' ,
	'roadtrip' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/roadtrip.svg' ,
	'romance' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/romance.svg' ,
	'seasports' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/sea.svg' ,
	'shopping' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/shopping.svg' ,
	'sun' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/sun.svg' ,
	'unusual' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/ballon.svg' ,
	'wellness' => AF_THEME_DIR_URI . '/assets/img/af-biblio/ENVIES/spa.svg' ,
];

$destinations_ids_param = $_POST['destinations_ids'] ?? '';
$destinations_ids = explode(',', $destinations_ids_param);
asort($destinations_ids);
$cache_key = $site_config['afmm_cache']['results_page_destinations']['key'].'_'.implode("_",$destinations_ids);
$cache_time = $site_config['afmm_cache']['results_page_destinations']['time'];
$destinations = get_data_from_cache($cache_key,'results_page_destinations',$cache_time,function() use ($destinations_ids){
    return get_posts(
        array(
            'post_type' => 'destination',
            'orderby' => 'post__in', 
            'post__in' => $destinations_ids,
            'posts_per_page' => -1,
        )
    );
});

$destinations_count = count($destinations);
$results_s_suffix = $destinations_count != 1 ? 'S' : '';

?>
<section>
    <div class="container">
        <div class="mb-2">
    <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['archive-destination']['ad1'] ."]") ;?>
    </div>
        <?php get_breadcrumb(); ?>
        <h2 class="destination-main-title mt-0"><?php echo $destinations_count." "; _e(' RÉSULTAT'. $results_s_suffix .' DE RECHERCHE', AFMM_TERMS);  ?></h2>
    </div>
</section>

<section class="destination-results-main-container">
    <div class="container destination-results-container" >
        <div class="container">
            <h3 class="dest-search-title mb-3">
                <?php _e('VOS TOP DESTINATIONS', AFMM_TERMS); ?>
            </h3>
        </div>
        <div class="row destination-row">
                <div class="destination-container">
                    <?php 
                        foreach (array_slice($destinations, 0, 3) as $key => $destination) {
                            $destination_iata = get_post_meta($destination->ID, 'origin_code', true);
                            $background_url = wp_get_attachment_url( get_post_thumbnail_id($destination->ID) );
                            $destination_url = get_permalink($destination->ID);
                            $price = get_post_meta($destination->ID, 'lowestPrice', true) ?? '-';
                            $title2_destination = __("Vol à partir de ",AFMM_TERMS)  . $price . "&euro; " . __("A/R*",AFMM_TERMS);
                            $depart = 'PAR';
                            $envies = wp_get_post_terms($destination->ID, 'envies') ?? [];
                            $envies = array_slice($envies, 0, 3);
                            ?>
                            <div class="destination-single-main-container-results">
                                <img class="destination-single-image" src="<?php echo $background_url ?>" loading="lazy">
                                <div class="destination-single-image-overlay"></div>
                                <div class="destination-single-container">
                                    <div class="destination-single-envies-container">
                                        <?php 
                                            foreach ($envies as $envie) {
                                            ?>
                                                <div class="destination-single-envies">
                                                    <img src="<?php echo envies_imgs[$envie->slug]; ?>">
                                                </div>
                                            <?php
                                            }
                                        ?>
                                    </div>
                                    <span class="destination-single-content"><?php echo $destination->post_title ?></span>
                                    <span class="destination-single-title"><?php echo $title2_destination ?></span>
                                    <a class="btn destination-single-button-guide" href="javascript:void(0);" data-href="<?php echo $destination_url ?>"><?php _e('DÉCOUVRIR LE GUIDE', AFMM_TERMS); ?></a>
                                    <a id='rdh_guide_search' class="btn destination-single-button-vol" href="javascript:void(0);" data-href="<?php echo generate_vol_url($depart ,$destination_iata). '&' . generate_utm_params('Hover','RDC'); ?>"><?php _e('RECHERCHER UN VOL', AFMM_TERMS); ?></a>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
        </div>
    </div>
</section>

<?php 
    if($destinations_count > 3) {
?>
<section>
    <div class="container destination-results-container">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="row justify-content-md-center justify-content-lg-between" >
                    <?php foreach (array_slice($destinations, 3) as $destination) : ?>
                        <?php
                        $title_destination = $destination->post_title;
                        $link_destination = get_permalink($destination->ID);
                        $image_html = get_the_post_thumbnail($destination->ID, 'large', ["class" => "post_image"]);
                        $price = get_post_meta($destination->ID, 'lowestPrice', true) ?? '-';
                        $title2_destination = __("Vol à partir de ",AFMM_TERMS)  . $price . "&euro; " . __("A/R*",AFMM_TERMS);
                        ?>
                        <div class="post">
                            <?php include STYLESHEET_DIR . '/template-parts/country/preview_destination_post.php'; ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="col-lg-4 col-12 d-flex justify-content-center p-0">
            <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['archive-destination']['ad2'] ."]") ;?>
            </div>
        </div>
</div>
</section>
<?php 
    }

get_footer();