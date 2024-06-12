<?php if ($destinations_countries) : ?>
    <h2 class="destination_title"><?php _e('ces destinations devraient vous tenter', AFMM_TERMS) ?></h2>
    <div class="row">
        <?php foreach ($destinations_countries as $destination) : ?>
            <?php
            $title_destination = $destination->name;
            $link_destination = get_term_link($destination, 'destinations');
            $image_url = get_term_meta($destination->term_id, 'pictureUrl', true);
            $image_html = '<img class="post_image" src="' . $image_url . '"/>';
            $price = get_post_meta($destination->ID, 'lowestPrice', true) ?? '-';
            $title2_destination = __("Vol à partir de ",AFMM_TERMS)  . $price . "&euro; " . __("A/R*",AFMM_TERMS);
            $preview_type = 'destination';
            $destination_vol_url = false;
            ?>
            <div class="col-md-4 post">
                <?php include STYLESHEET_DIR . '/template-parts/country/preview_destination_post.php'; ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>