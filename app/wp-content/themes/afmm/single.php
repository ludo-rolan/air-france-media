<?php
global $post;
global $site_config;

$post_id = $post->ID;
$article_type = get_post_meta((int) $post_id, 'post_display_type_name', true);
$post_type = $post->post_type;
$is_diapo = $article_type == 'DIAPO';
$is_edito = ($article_type == 'EDITO' || $post_type == 'travel-guide' || $is_diapo);
$fullwidth_img = get_the_post_thumbnail_url($post_id, 'full');
$category_detail = get_the_category($post_id);
$author_id =  get_the_author_meta('display_name', $post->post_author);
get_header();
//if(!$is_diapo) echo do_shortcode("[dfp id='habillage']") ;
include(locate_template("template-parts/tg_bandeau.php"));
?>
<?php
$partner = "";
if (has_term('', 'partenaires')) {
    $partenaires = get_the_terms($post->ID, 'partenaires');
    if ($partenaires[0]) {
        $partner = '<span class="text-dark"> | EN PARTENARIAT AVEC ';
        $image_id = get_term_meta($partenaires[0]->term_id, 'partenaires-image-id', true);
        $post_thumbnail_img = wp_get_attachment_image_src($image_id, 'thumbnail');
        if ($post_thumbnail_img) {
            $partner .= '<img class="ml-1 mb-1" src="' . $post_thumbnail_img[0] . '" alt="' . $partenaires[0]->name . '" />';
        } else {
            $partner .= $partenaires[0]->name;
        }
        $partner .= "</span>";
    }
}
?>
<section>
    <div class="container">
        <!-- ad -->
        <div id="megabanner_top">
        <?php echo do_shortcode("[dfp id='masthead_haut']") ?>
        </div>
        <?php echo do_shortcode("[dfp id='mobile_1']") ?>
        <!-- ad -->

        <div class="hp_bandeau row <?php if ($is_diapo) echo 'mb-0 pb-0'; ?>">
            <span class="hp_bandeau_cat">
                <?php get_breadcrumb(); ?>
            </span>
            <h1 class="hp_bandeau_title">
                <?php the_title(); ?>
            </h1>
            <div class="hp_bandeau_info">
                <?php echo get_the_date('l j F Y') . ' | ' . $author_id . '' . $partner;  ?>
            </div>
        </div>
    </div>
    <?php if (!$is_diapo) { 
        $resize_thumb = get_post_meta((int) $post_id, 'resize_thumb_resize', true);
        $resize_thumb_class = $resize_thumb === "true" ? "resize_thumb" : "";
        $resize_next_container = $resize_thumb === "true" ? "resize_next_container " : "";
        ?>
        <div class="hp_bandeau_image img-responsive responsive--full parallax--bg <?php echo $resize_thumb_class; ?>" style="background-image:url(' <?php echo $fullwidth_img; ?>')">
        </div>
    <?php } ?>
</section>
<!--
    TODO:
    Integrating special container for the post content in order to pull images
    out of the multi-columns
    if no other solution is found
-->
<div class="container <?php echo $resize_next_container; if($is_diapo) echo 'diapo-container' ?>">

    <?php
    do_action('read_time',$is_diapo);
    $x = ($is_edito) ? 8 : 12;
    do_action('before_the_content');
    ?>
    <div class="row">
        <div class="col-md-<?php echo $x; ?> ">
            <div class="post-excerpt">
                <?php
                if(!$is_diapo){ 
                    the_excerpt();
                    do_action('after_the_excerpt');  
                }
                ?>
            </div>
            <div class="post-content" id="<?php echo ($is_edito) ? "edito" : ""; ?>">
                <?php the_content();

                do_action('after_the_content_all_type') ;

          
                ?>
            </div>
            <?php if ($is_edito) {
                do_action('after_the_content');
                include(locate_template('/template-parts/a_lire_aussi.php'));
            }



            ?>



        </div>
        <?php
        if ($is_edito) {
        ?>
            <nav class="col-md-4 sidebar <?php if($is_diapo) echo 'diapo-sidebar' ?>">
                <div class="row flex-column sticky-top single-sticky">
                    <?php
                    if(!$is_diapo) 
                        echo do_shortcode("[dfp id='mpu_haut']");
                    get_sidebar('plus-lus');
                    //if(!$is_diapo)
                        echo do_shortcode("[dfp id='mpu_milieu']");
                    ?>
                </div>
            </nav>
        <?php
        }
        ?>
    </div>
</div>

<?php
if (!$is_edito) {
    do_action('after_the_content');
}
?>
</div>
</div>

<?php
    do_action('single_before_footer');


get_footer();
