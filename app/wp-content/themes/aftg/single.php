<?php

global $post;
global $site_config;
$post_id = $post->ID;
$city = get_the_terms($post_id,'destinations')[0]->name;
$image_dimensions = apply_filters('select_cropped_img', ['custom_size' => 'thumb-w-360', 'original_size' => '1536x768']);
$fullwidth_img = get_the_post_thumbnail_url($post_id, $image_dimensions);
get_header();
?>
<?php
$partner="";
if ( has_term('', 'partenaires') ) {
    $partenaires = get_the_terms( $post->ID, 'partenaires' );
    if($partenaires[0]){ 
        $partner='<span class="text-dark"> | EN PARTENARIAT AVEC ';
        $image_id = get_term_meta( $partenaires[0]->term_id, 'partenaires-image-id', true );
        $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'thumbnail' );
        if($post_thumbnail_img){
            $partner.= '<img class="ml-1 mb-1" src="' . $post_thumbnail_img[0] . '" alt="' . $partenaires[0]->name . '" />';
        }
    else {
            $partner.=$partenaires[0]->name;
        }
            $partner.="</span>";
    }
}
?>
<?php echo do_shortcode("[dfp id='mobile_1']") ?>
<section>
    <div class="container">
        <div class="adr_bandeau row ">
            <span class="adr_bandeau_cat">
            <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single']['ad1'] ."]") ; ?>
                <?php get_breadcrumb(); ?>
            </span>
        </div>
    </div>
    <?php 
    $resize_thumb = get_post_meta((int) $post_id, 'resize_thumb_resize', true);
    $resize_thumb_class = $resize_thumb === "true" ? "resize_thumb" : "";
    $resize_next_container = $resize_thumb === "true" ? "resize_next_container " : "";
    ?>
    <div 
            class="adr_bandeau_image img-responsive responsive--full parallax--bg <?php echo $resize_thumb_class; ?>"
            style="background-image:url(' <?php echo $fullwidth_img; ?>')"
            >
    </div>
</section>

<div class="container <?php echo $resize_next_container; ?>">
        <?php     do_action('read_time',false); ?>
        <div class="row">
            <div class="col-md-8">
            <?php do_action('before_the_content'); ?>
            <div class="post-excerpt">
                <?php
                    the_excerpt();
                    do_action('after_the_excerpt'); 
                ?>
            </div>
            <div class="post-content" id="edito">
                <?php the_content();
                ?>
            </div>
            <!-- l'ajout de wemap -->
            <div class="carteadresse">
                <h2 class="destination_title"><?php _e("Carte de");echo " " . $city?></h2>
                <div class="row">
                    <div id="map-container" class="col" style="height:600px"></div>
                </div>
            </div>
            <?php 
                do_action('after_the_content');
            
            ?>
        </div>
        
        <nav class="col-md-4 d-flex flex-column align-items-center sidebar">
            
            <div class="row flex-column sticky-top single-sticky">
                <?php
                echo do_shortcode("[dfp id=". $site_config['dfp-position']['single']['ad2'] ."]") ;
                get_sidebar('plus-lus');
                echo do_shortcode("[dfp id=". $site_config['dfp-position']['single']['ad3'] ."]") ;

                ?>
            </div>
        </nav>
      
    </div>
</div>
</div>
<!-- j'ai testé la map en front tt est bon, il reste la confirmation client pour l'emplacement de la map -->
<!-- <div id="map-container" class="col-md-8" style="height:600px"> -->
</div>

<?php

get_footer();
