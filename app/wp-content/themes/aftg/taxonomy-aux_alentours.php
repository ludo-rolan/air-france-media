<?php
get_header();
$current_term = get_queried_object();
$alentour_infos = get_term_meta($current_term->term_id);
$main_img = $alentour_infos['aux_alentours-image-id'][0];
$page_title = $alentour_infos['aux_alentours-title_preview'][0];
$page_content = $current_term->description;
$alentour_name = $current_term->name;
$alentour_map = $alentour_infos['localisation_latitude'][0];
$term_param = '&aux_alentours_id='.$current_term->term_id;
$filtres = get_terms(['taxonomy' => 'filtre', 'parent' => 0]); 
$adresses = get_posts(
    [
        'post_type' => 'adresse',
        'numberposts' => 5,
        'tax_query' => [
            [
                'taxonomy' => 'aux_alentours',
                'field' => 'term_id',
                'terms' => [$current_term->term_id],
                'operator' => 'IN',
            ]
        ]
    ]
);

?>
<!-- breadcrumb -->
<section>
    <div class="container">
    <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['tax_aux_alentours']['ad1'] ."]") ;?>
        <?php get_breadcrumb(); ?>
    </div>
    <div class="destination_image img-responsive responsive--full parallax--bg" style="background-image:url(' <?php echo $alentour_infos['aux_alentours-image-id'] != null ? wp_get_attachment_image_url($main_img) : '' ?>')">
    </div>
</section>
<div class="container">
    <!-- intro -->
    <div class="bloc" id="intro">
        <?php include locate_template('/template-parts/alentours/intro_bloc.php'); ?>
    </div>
    <!-- adresses -->
    <div class="bloc" id="adresses">
        <?php include locate_template('/template-parts/alentours/adresse_bloc.php'); ?>
    </div>
    <!-- carte -->
    <div class="bloc" id="carte">
        <?php include locate_template('/template-parts/alentours/carte_bloc.php'); ?>
    </div>
    <!-- maillage -->
    <div class="bloc" id="readmore">
        <?php include locate_template('/template-parts/alentours/readmore_bloc.php'); ?>
    </div>
</div>
<?php
get_footer();
?>