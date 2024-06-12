<?php
$image_ids = unserialize($post_metas['slideshow'][0])['slideshow_pictures'];
$count = 0;
?>
<?php if(!empty($image_ids)){?>
<div class="bloc" id="images">
<h2 class="destination_title"><?php echo $post_title; ?><?php echo _e(' en images', AFMM_TERMS) ?></h2>
<div class="row">
    <!-- c'est la ou on trouve la galerie -->
    <div class="col-md-8">
        <div class="gallery">
            <div class="gallery_slider">
                <div class="gallery_slider_cercle-left owl-prev<?php echo $count; ?>"><img loading="lazy" class="gallery_slider_cercle_arrow" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/arrow-left.png'; ?>" /></div>
                <div class="owl-carousel gallery_carousel">
                    <?php
                        $image_ids = explode(',', $image_ids);
                        foreach ($image_ids as $image_id) {
                            // traduire l'id de l'image en utilisant wpml_object, si l'image a une traduction
                            // on retourne l'image traduite, sinon on obtient l'image originale 
                            $image_id = apply_filters('wpml_object_id', $image_id, 'attachment', TRUE, ICL_LANGUAGE_CODE);
                            $caption = get_post($image_id)->post_excerpt;
	                        $image_dimensions = apply_filters('select_cropped_img', ['custom_size' => 'thumb-w-278-h-200', 'original_size' => 'thumb-w-784-h-445']);
	                        $img_html = wp_get_attachment_image($image_id, $image_dimensions); ?>
                            <div class="item">
                                <?php echo $img_html; ?>
                                <div class="gallery_infos_wrapper">
                                    <div class="gallery-title">
                                        <a href="<?php the_permalink(); ?>"><span class="gallery-num"></span> <?php echo $caption ?></a>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
                </div>
                <div class="gallery_slider_cercle-right owl-next<?php echo $count; ?>"><img loading="lazy" class="gallery_slider_cercle_arrow" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/arrow-right.png'; ?>" /></div>
            </div>
            <div class="gallery_infos">
                <div class="gallery_trait_parent">
                    <div class="gallery_trait">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
    <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single_destination']['ad2'] ."]") ;
                                        $count++; ?>
    </div>
</div>
</div>
<?php } ?>