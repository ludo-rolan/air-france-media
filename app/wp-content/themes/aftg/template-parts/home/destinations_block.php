<?php
global $option_hp, $site_config;
$key_option = 'aftg_hp_options';
$option_hp = get_option($key_option);


$destinations_ids = explode(',', $option_hp['aftg_hp_options_destinations_hp'] ?? "");
$cache_key = $site_config['aftg_cache']['hp_destinations_block']['key'] . '_' . implode("_", $destinations_ids);
$cache_time = $site_config['aftg_cache']['hp_destinations_block']['time'];
$destinations = get_data_from_cache($cache_key, 'hp_destinations_block', $cache_time, function () use ($destinations_ids) {
    return get_posts(
        array(
            'post_type' => 'destination',
            'orderby' => 'post__in',
            'post__in' => $destinations_ids,
            'posts_per_page' => -1,
        )
    );
});



function single_destination($destination, $option_hp, $index)
{
    $destination_option_content = $option_hp['aftg_hp_options_destinations_content_' . $index] ?? "";
    $image_dimensions = apply_filters('select_cropped_img', ['thumb-w-300-h-280']);
	$background_url = wp_get_attachment_image_url(get_post_thumbnail_id($destination->ID), $image_dimensions);
	$destination_url = get_permalink($destination->ID);
?>
    <a class="destination-single-main-container" href="<?php echo $destination_url ?>">
        <img class="destination-single-image" src="<?php echo $background_url ?>" loading="lazy">
        <div class="destination-single-image-overlay"></div>
        <div class="destination-single-container">
            <span class="destination-single-content"><?php _e($destination_option_content,AFMM_TERMS) ?></span>
            <span class="destination-single-title"><?php echo $destination->post_title ?></span>
        </div>
    </a>
<?php
}
include(locate_template('template-parts/home/destinations_search_block.php'));
?>


<h2 class="destination-main-title dest-search_title_home"><?php _e("LES DESTINATIONS DU MOIS", AFMM_TERMS) ?></h2>
<section class="container destination-main-container mb-4">
    <div class="row destination-row">
        <div class="destination-container destination-container-first">
            <?php
            $destinations_first_block = array_slice($destinations, 0, 4);
            foreach ($destinations_first_block as $key => $destination) {
                single_destination($destination, $option_hp, $key + 1);
            }
            ?>
        </div>
        <?php echo do_shortcode("[dfp id=" . $site_config['dfp-position']['homepage']['ad2'] . "]"); ?>
        <?php  if (!get_omep_val('couper_position_mobile_102_0741')) {?>
        <div class="pt-5">
            <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['homepage']['mobile_ad2'] ."]") ; ?>
        </div>
        <?php } ?>
    </div>

    <?php if (count($destinations_ids) > 4) {
        $destinations_second_block = array_slice($destinations, 4, 3);
    ?>
        <div class="row destination-row">
            <div class="destination-container">
                <?php
                foreach ($destinations_second_block as $key => $destination) {
                    single_destination($destination, $option_hp, $key + 5);
                }
                ?>
            </div>
        </div>
    <?php
    }
    ?>
</section>