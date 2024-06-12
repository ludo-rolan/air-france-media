<?php
    global $site_config;
    global $wpdb;
    $post_meta_table = $wpdb->prefix . 'postmeta';
    get_header();
    $current_term = get_queried_object();
    $main_img = get_term_meta( $current_term->term_id,'pictureUrl',true);
    $destinations_cities = get_posts(
        [
            'post_type' => 'destination',
            'numberposts' => -1,
            'orderby'     => 'post_title',
            'order'       => 'ASC',
            'tax_query' => [
                [
                    'taxonomy' => 'destinations',
                    'field' => 'term_id',
                    'terms' => [$current_term->term_id],
                    'operator' => 'IN',
                ]
            ]
        ]
    );
    $post_ids = join(",",wp_list_pluck($destinations_cities,'ID'));
    $query = "SELECT SQL_CACHE post_id, cast(meta_value as unsigned) as price FROM `wp_postmeta` WHERE meta_key = 'lowestprice' and post_id IN ({$post_ids}) ORDER BY CASE WHEN price IS NULL THEN 1 ELSE 0 end, price;";
    // $query = "SELECT SQL_CACHE post_id, CAST(REGEXP_REPLACE(`meta_value`, '[^0-9]+', '') as unsigned) as price FROM {$post_meta_table} WHERE `meta_key` = 'price' AND `post_id` IN ({$posts_ids}) ORDER BY `price` ASC LIMIT 2; ";
    $cheaper_price = $wpdb->get_results($wpdb->prepare($query),ARRAY_A);
    if ( $current_term->parent > 0 ) {
        $destinations_countries = get_terms( array(
            'taxonomy'  => 'destinations',
            'parent'    => $current_term->parent,
            'exclude'   => $current_term->term_id,
            'fields'    => 'all',
            'number' => "3"
        ) );
    }
?>

<!-- breadcrumbs -->
<section>
    <div class="container">
    <?php echo do_shortcode("[dfp id='mobile_1']") ;?>
    <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['taxonomy_destinations']['ad1'] ."]") ;?>
            <?php get_breadcrumb($cheaper_price); ?>
    </div>
    <div
            class="destination_image img-responsive responsive--full parallax--bg"
            style="background-image:url(' <?php echo $main_img ?>')"
    >
    </div>
</section>
<div class="container">
    <div class="destination_content"></div>
    <!-- découvrir -->
    <?php  if (!get_omep_val('couper_position_mobile_102_0741')) {
        echo do_shortcode("[dfp id='mobile_2']") ;
    }?>
        <?php include STYLESHEET_DIR . '/template-parts/country/discover_destinations_bloc.php'; ?>
    <!-- en savoir plus -->
        <div class="bloc" id="maillage">
            <input type="hidden" name="data-slug" value="<?php echo $current_term->slug; ?>">
            <input type="hidden" name="data-lang" value="<?php echo ICL_LANGUAGE_CODE ?>">
            <input type="hidden" name="data-name" value="<?php _e('en savoir plus sur ', AFMM_TERMS);echo $current_term->name; ?>">
            <input type="hidden" name="data-host" value="<?php echo IS_PREPROD ? $site_config['afmm']['preprod'] : $site_config['afmm']['prod'] ?>">
        </div>
    <!-- devraient tenter -->
        <?php include STYLESHEET_DIR . '/template-parts/country/suggestion_destinations_bloc.php'; ?>
    <div class="destination_info">
        <p>
        <?php _e("* Tous les montants sont en EUR. Les taxes, surcharges et frais de réservation sont compris.<br>
            Les prix affichés peuvent varier en fonction de la disponibilité du tarif. Le tarif est garanti dès que vous obtenez la référence de votre réservation.",AFMM_TERMS)
        ?>
        </p>
    </div>
</div>
<?php 
    get_footer();
?>