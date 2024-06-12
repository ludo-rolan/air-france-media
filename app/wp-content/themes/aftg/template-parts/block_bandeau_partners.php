
<?php

if ( has_term('', 'partenaires') ) {
    $partenaires = get_the_terms( $post->ID, 'partenaires' );
?>
<div class="container bandeau_partners">
    <div class="d-flex flex-wrap">
        <div class="col-md-3 col-12 d-flex justify-content-start align-items-center pl-4 bandeau_partners_title">
            <p><?php _e('À DÉCOUVRIR', AFMM_TERMS); echo ' :'; ?></p>
        </div>
        <div class="col-md-9 col-12 d-flex flex-wrap align-items-center bandeau_partners_logos">
            <?php
            foreach($partenaires as $partenaire){
                $partenaire_metas = get_term_meta( $partenaire->term_id, '', true );
                $image_id = $partenaire_metas['partenaires-image-id'][0];
                $post_thumbnail_img = wp_get_attachment_image_url( $image_id, 'full' );
                if(!empty($post_thumbnail_img)){
                    $logo_href = $partenaire_metas['partenaires-url'][0] ? update_external_links('href="'.$partenaire_metas['partenaires-url'][0].'"') : '';
                ?>
                    <div class="d-flex justify-content-center align-items-center bandeau_partners_item">
                        <a <?php echo $logo_href; ?> target="_blank">
                            <img loading="lazy" src="<?php echo $post_thumbnail_img; ?>" />
                        </a>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
}
