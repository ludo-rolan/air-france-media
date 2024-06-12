<?php
$taxonomies = array(
    array('name' => __('envies',AFMM_TERMS), 'title' => __('SÉLECTIONNEZ UNE OU PLUSIEURS ENVIES',AFMM_TERMS)),
    array('name' => __('destinations',AFMM_TERMS), 'title' => __('SÉLECTIONNEZ UNE OU PLUSIEURS DESTINATIONS',AFMM_TERMS)),
    array('name' => __('mois',AFMM_TERMS), 'title' => __('SÉLECTIONNEZ UNE OU PLUSIEURS MOIS',AFMM_TERMS)),

);
foreach ($taxonomies  as $taxonomy) {
    $terms_array = array();
    $terms = get_terms(['taxonomy' => $taxonomy['name'], 'hide_empty' => false,]);
    foreach ($terms as $term) {
        $image_id = get_term_meta( $term->term_id, $taxonomy['name'].'-image-id', true );
        $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'thumbnail' );
        array_push($terms_array, array($term->name, $term->slug, $post_thumbnail_img));
    }
?>
    <form class="<?php echo $taxonomy['name'] ?> ">
        <?php Multi_Select_Filter::render_filters($terms_array, $taxonomy['name'], $taxonomy['title']); ?>
    </form>
<?php } ?>

<button type="button" text="Envies" id="envies" ?><?php _e("Envies",AFMM_TERMS) ?></button>
<button type="button" text="Destinations" id="destinations" ?><?php _e("Destinations",AFMM_TERMS) ?></button>
<button type="button" text="Mois" id="mois" ?><?php _e("Mois",AFMM_TERMS) ?></button>

<style>
    .envies {
        display: none;
    }

    .destinations {
        display: none;
    }

    .mois {
        display: none;
    }
</style>
<?php ?>