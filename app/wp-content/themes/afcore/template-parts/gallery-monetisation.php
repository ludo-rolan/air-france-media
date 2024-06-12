<?php

global $post;
global $site_config;
$post_gallery = get_post_meta($post->ID, 'monetisation', true);
$gallery_images_ids = explode(',', $post_gallery['monetisation_diapo_gallery']);
$gallery_caption = $post_gallery['monetisation_légende'];
if (!$site_config['ismobile']) {
?>
    <div class="row pt-4  ">
        <div class="col-md-8">
            <?php
            echo "<div class='post-excerpt mt-0'>";
            the_excerpt();
            echo "</div>";
            ?>
            <figure class="wp-block-gallery is-cropped gallery-slider-monetisation mb-0">
                    <?php
                    foreach ($gallery_images_ids as $image_id) {
                        if($image_id != ""){
                            $image_caption = wp_get_attachment_caption($image_id);
                            $image_url = wp_get_attachment_url($image_id);
                            $image_title = get_the_title($image_id);
                        }

                    ?>
                            <figure class="wp-block-image">
                                <img loading="lazy" src="<?php echo $image_url; ?>">

                                <figcaption>
                                    <h3><?php echo $image_title ?></h3>
                                    <p><?php echo $image_caption; ?></p>
                                </figcaption>
                            </figure>
                    <?php
                    }
                    ?>
            </figure>
            <div class=" mt-4 d-flex">
                <button class="gallery-slider-monetisation-prev gallery-slider-arrow-blur col-5 col-sm-4 text-white border-0 p-2 mr-2">
                    <?php _e('PRÉCÉDENT', AFMM_TERMS); ?>
                </button>
                <button class="gallery-slider-monetisation-next w-100 text-white border-0 p-2">
                    <?php _e('SUIVANT', AFMM_TERMS); ?>
                    <img class="ml-3" loading="lazy" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/arrow-right.png'; ?>">
                </button>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <?php echo do_shortcode("[dfp id='mpu_haut']"); ?>
        </div>
    </div>
<?php } else {
?>
    <div class="row pt-4">
        <div class="col-md-12 mb-2 mt-2">
            <?php
            echo "<div class='post-excerpt mt-0'>";
            the_excerpt();
            echo "</div>";
            ?>
        </div>
        <?php
        $i = 0;
        $total_images = count($gallery_images_ids);
        foreach ($gallery_images_ids as $image_id) {
            $image_caption = wp_get_attachment_caption($image_id);
            $image_url = wp_get_attachment_url($image_id);
            $image_title = get_the_title($image_id);

            $i++;
        ?>
            <div class="diapo_monetisation_mobile col-md-12 mb-2 mt-2" id="<?php echo $i ?>" data-id="<?php echo sanitize_title($gallery_caption) . '-' . $image_id; ?>">
                <figure class="wp-block-gallery is-cropped gallery-slider-monetisation mb-0">
                    <div class="gallery-slider-count-container pb-0 mt-3">
                        <span class="gallery-slider-count-current"><?php echo sprintf("%02d", $i); ?></span>
                        <div class="gallery-slider-count-line-monetisation"></div><span class="gallery-slider-count-total"><?php echo sprintf("%02d", $total_images) ?></span>
                    </div>
                    <figcaption class="blocks-gallery-caption">
                        <h3><?php echo $image_title; ?></h3>
                        <p><?php echo $image_caption; ?></p>
                    </figcaption>
                    <figure class="wp-block-image">
                        <img loading="lazy" src="<?php echo $image_url; ?>">
                    </figure>

                </figure>
            </div>
            <?php if(!get_omep_val('enlever_diapo_mobile_0662')) {?>
            <div class="col-md-12 p-0 ">
                <?php

                $j = $i + 1;
                echo do_shortcode("[dfp id='mobile_" . $j . "']");
                ?> 
                </div> <?php
                }} 
                do_action('after_diapo_monetisation_mobile', $i ) ;

                ?>


    </div>

<?php
}

do_action('after_last_diapo_monetisation_mobile' ) ;


?>