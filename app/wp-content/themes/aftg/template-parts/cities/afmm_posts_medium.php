<?php if ($alentours) : $count = 0 ?>
<div class="bloc" id="alentours">
    <h2 class="destination_title"><?php _e('à découvrir aux alentour de  ', AFMM_TERMS);echo $post_title; ?></h2>
    <div class="row">
        <!-- att le maillage -->
        <?php foreach ($alentours as $alentour) : $count++ ?>
            <?php
            if ($count == 3)  break;
            $afmm_link = "/?aux_alentours=".$alentour->slug;
            $afmm_category = "circuits";
            $afmm_metas = get_term_meta($alentour->term_id, '', true);
            $afmm_title = $afmm_metas['aux_alentours-title_preview'][0];
            $afmm_id = $afmm_metas['aux_alentours-image-id'][0];
            ?>
            <div class="col-md-6 post">
                <?php include STYLESHEET_DIR . '/template-parts/cities/preview_destination_post_medium.php' ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>