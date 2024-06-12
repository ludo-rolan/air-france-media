<?php
global $post;
$post_id = $post->ID;
$post_title = $post->post_title;
$dest_metas = get_post_meta($post->ID);
$practical_infos = array(
                        "localCalendar",
                        "DestinationWeather",
                        "airports",
                        "transportation",
                        "touristInformation",
                        "currency",
                        "medical",
                        "administrativeProcedures",
                        "usefulAddresses",
                        );
$depart='PAR';
$destination = get_post_meta((int) $post_id,'origin_code',true);
$url_redirection = generate_vol_url($depart, $destination) . '&' . generate_utm_params('StickyMenu','RDC');

?>
<div class="container">

<div class="infosPratiques_pageTitle">
    <h1 class=""><?php _e('Les infos pratiques de ', AFMM_TERMS); echo $post_title; ?></h1>
</div>
<div class="row">
    <div class="col-md-4 " style="margin-top: -100px;">
        <div class="sticky-top" style="padding-top: 100px;">
            <!--include infos-blocs.php-->
            <div class="infosPratiques_menu">
                <?php include locate_template('/template-parts/cities/infos_bloc.php'); ?>
            </div>
            <div class="infosPratiques_buttons">
                <a href="" id="infosPratiques_buttons_blue" class="btn af-btn infosPratiques_buttons_blue"><?php _e("retour au guide de ", AFMM_TERMS);echo $post_title; ?></a>
                <a id="rdh_guide_stickymenu"  href="<?php echo $url_redirection; ?>" target="_blank" class="btn af-btn"><?php _e('rechercher un vol vers ', AFMM_TERMS);echo $post_title; ?></a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?php
    foreach ($practical_infos as $key) {
        if(strcmp($key, 'DestinationWeather') == 0){
            $meteo_infos_data = maybe_unserialize($dest_metas[$key][0]);
            $title = $meteo_infos_data[$key .'_title'];
            $intro = $meteo_infos_data[$key .'_introduction'];
            ?>
            <div class="infosPratiques_wrapper"; id="weather" >
                <h2 class="infosPratiques_title"><?php echo $title; ?></h2>
                <div class="infosPratiques_intro"><?php echo $intro; ?></div>
                <?php echo do_shortcode('[meteo_table]'); ?>
            </div>
            <?php
        }
        else{
            $infos_pratique_data = maybe_unserialize($dest_metas[$key][0]);
            $title = $infos_pratique_data[$key .'_title'];
            $description = $infos_pratique_data[$key .'_description'];
            ?>
             <div class="infosPratiques_wrapper" id="<?php echo $key; ?>" >
                <h2 class="infosPratiques_title"><?php echo $title; ?></h2>
                <div class="infosPratiques_description"><?php echo $description; ?></div>
            </div>
        <?php
        }
    }
?>
        <div class="infosPratiques_wrapper" id="essentialPhrases">
            <?php echo do_shortcode("[block_essentialPhrases]"); ?>
        </div>
        <?php echo do_shortcode("[bon_a_savoir]") ?>
    </div>
   
</div>
    <!-- bandeau logos partenaires -->
    <?php do_action('bandeau_logos_partenaires'); ?>
</div>