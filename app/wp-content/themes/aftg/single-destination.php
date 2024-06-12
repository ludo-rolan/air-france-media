<?php
global $site_config;
get_header();
$main_img = get_the_post_thumbnail_url();
$post_id = get_the_ID();
$post_title = get_the_title();
$post_terms = wp_get_post_terms($post_id, 'destinations');
$post_term_parent = $post_terms[0];
$get_term = get_queried_object();
$post_metas = get_post_meta($post_id);

$city_localisation = unserialize($post_metas['localisation'][0]);
$city_currency_symbol = unserialize($post_metas['currency'][0])['currency_symbol'];
$city_currency_label = unserialize($post_metas['currency'][0])['currency_label'];
$city_flight_duration = preg_replace( '/[PTM]/', '', unserialize($post_metas['time'][0])['time_effectiveFlightDuration'] );
$city_language = explode(',', unserialize($post_metas['destinationSpokenLanguages'][0])['destinationSpokenLanguages_code'])[0];
$city_bandeau_infos = [
    'clock' => 'clock',
    $city_language => 'globe',
    $city_flight_duration => 'flight',
];
$url_redirection = generate_vol_url('PAR', $post_metas['origin_code'][0]) . '&' . generate_utm_params('Searchbar','RDC');
$alternate_destination_ids = unserialize($post_metas['alternateDestinations_ids'][0]);
// $alternate_destination_ids => string array, we convert it to int arrray using array_map
$alternate_destination_ids ? $alternate_destination_ids = array_map('intval',$alternate_destination_ids) : $alternate_destination_ids = false;
// we check if alternate_destination_ids is not null then we call posts. We got an error if we skiped this check condition :')
if($alternate_destination_ids){
    $alternate_destination_posts = get_posts(
        [
            'include' => array_slice($alternate_destination_ids,0,3),
            'post_type' => "destination",
        ]
    );
}
$page_title = $post_metas['origin_title'][0];
$map = unserialize($post_metas['map'][0]);
$alentours = get_the_terms($post_id,'aux_alentours');
$dailymotion_id = explode(',', unserialize($post_metas['dailymotion'][0])['dailymotion_id'])[0];

function get_destination_adresse($post_term_arg, $number_posts_args)
{
    return get_posts(
        [
            'post_type' => 'adresse',
            'numberposts' => 7,
            'post__not_in' => [get_the_ID()],
            'tax_query' => [
                [
                    'taxonomy' => 'destinations',
                    'field' => 'term_id',
                    'terms' => [$post_term_arg],
                    'operator' => 'IN',
                ]
            ]
        ]
    );
}

function get_destination_info_widget($content, $icon) {
    ?>
    <div class="destination_city_infos_single">
        <div class="d-block m-auto destination_city_infos_single_image">
            <img loading="lazy" class="d-block m-auto" src="<?php echo STYLESHEET_DIR_URI.'/assets/img/'.$icon.'.png'; ?>">
        </div>
        <span class="d-block m-auto text-uppercase" id="<?php if($icon=="clock") echo 'destination_city_time'?>"><?php if($icon !=="clock") echo $content; ?></span>
    </div>
    <?php 
}
if(!isset($_GET['info_pratiques'])){
?>
<!-- breadcrumb -->
<section>
    <div class="container">
        <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single_destination']['ad1'] ."]") ?>
        <div class="pt-5">
            <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single_destination']['mobile_ad1'] ."]") ; ?>
        </div>
        <!-- changer l'image par AD -->
        <?php get_breadcrumb(); ?>
    </div>
    <div class="destination_image img-responsive responsive--full parallax--bg" style="background-image:url(' <?php //echo $main_img != null ? $main_img : '' ?>')">
        <?php
        $timelapse_id= apply_filters("unserialise_metabox",$post_metas,"mainVideo","mainVideo_videoPlayerId");
        if($timelapse_id) echo do_shortcode('[timelapse id="'.$timelapse_id.'"]');
        ?>
        <div class="w-100 d-flex flex-column align-items-center  destination_image destination_city_infos_container" >
            <div class="w-100 d-flex justify-content-around destination_city_infos" >
            <div class="destination_city_infos_single" title="<?php echo $city_currency_label; ?>">
                <div class="d-block m-auto destination_city_infos_single_image">
                    <img loading="lazy" class="d-block m-auto" src="<?php echo STYLESHEET_DIR_URI.'/assets/img/coins.png'; ?>">
                </div>
                <span class="d-block m-auto text-uppercase"><?php echo $city_currency_symbol; ?></span>
            </div>
                <?php 
                foreach ($city_bandeau_infos as $key => $value) {
                    if(!empty($key)) {
                        get_destination_info_widget($key, $value);
                    }
                }
                ?>
            </div>
            <?php
                if (preg_match('/^(?=.*cabinClass=).*$/', $url_redirection)) {
            ?>
            <a id="rdh_guide_header" href="<?php echo $url_redirection; ?>" class="btn af-btn text-uppercase px-2" target="_blank"><?php _e('RECHERCHER UN VOL VERS ', AFMM_TERMS); echo $post_term_parent->name; ?></a>
            <?php } ?>
         </div>
    </div>  
</section>
<div class="container">
    <!-- submenu -->
    <?php include locate_template('/template-parts/cities/submenu_bloc.php'); ?>

    <!-- les infos prarique sur madrid -->
    <?php include locate_template('/template-parts/cities/introduction_bloc.php'); ?>
    <?php if ($dailymotion_id != '') { ?>
                    <div class='dailymotion_video' id='dailymotionVideo' data-videoID="<?php echo $dailymotion_id ?>"></div>
    <?php } ?>
    <!-- les infos prarique sur madrid -->
    <?php include locate_template('/template-parts/cities/images_bloc.php'); ?>
    <?php  if (!get_omep_val('couper_position_mobile_102_0741')) {?>
    <div class="p-2">
            <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single_destination']['mobile_ad2'] ."]") ; ?>
    </div>
    <?php  }?>
    <!-- les infos prarique sur madrid -->
    <?php $param=6;include locate_template('/template-parts/cities/infos_bloc.php'); ?>

    <!-- nos adress à madrid -->
    <?php include locate_template('/template-parts/cities/adresses_bloc.php'); ?>

    
    <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single_destination']['ad3'] ."]") ; $count++; ?>
    
   

    <!-- carte de madrid -->
    <?php include locate_template('/template-parts/cities/carte_bloc.php'); ?>
    
    <!-- à découvrir aux alentour de madrid -->
    <?php include locate_template('/template-parts/cities/afmm_posts_medium.php'); ?>

    <!-- plus d'info -->
    <div class="bloc" id="maillage">
        <input type="hidden" name="data-slug" value="<?php echo $get_term->post_name ?>">
        <input type="hidden" name="data-lang" value="<?php echo ICL_LANGUAGE_CODE ?>">
        <input type="hidden" name="data-name" value="<?php _e('plus d\'articles sur ', AFMM_TERMS);echo $post_title; ?>">
        <input type="hidden" name="data-host" value="<?php echo IS_PREPROD ? $site_config['afmm']['preprod'] : $site_config['afmm']['prod'] ?>">
    </div>

    <!-- autres destination -->
    <div class="bloc">
        <?php include locate_template('/template-parts/cities/preview_posts_cities.php'); ?>
    </div>

    <!-- bandeau logos partenaires -->
    <?php do_action('bandeau_logos_partenaires'); ?>
    
    <div class="destination_info">
        <p>
            <?php _e("* Tous les montants sont en EUR. Les taxes, surcharges et frais de réservation sont compris.<br>
            Les prix affichés peuvent varier en fonction de la disponibilité du tarif. Le tarif est garanti dès que vous obtenez la référence de votre réservation." ,AFMM_TERMS)
            ?>

        </p>
    </div>
</div>
<?php
}
else if($_GET['info_pratiques']){
$param=12;
?>
<section class="infosPratiques_breadcrumb">
    <div class="container">
        <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['info_pratique']['ad1'] ."]") ?>
        <?php get_breadcrumb(); ?>
    </div>
</section>
<?php
include locate_template('/template-parts/cities/infos_pratiques.php');
}
?>
</div>
<?php
get_footer();
?>
<script>
    let timeZoneName = "<?php echo $post_metas['country_time_zone_name'][0]; ?>";
    currentTime(timeZoneName);
</script>
