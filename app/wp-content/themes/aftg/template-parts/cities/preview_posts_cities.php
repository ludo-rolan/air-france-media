<?php if ($alternate_destination_posts) : ?>
    <h2 class="destination_title"><?php _e('les autres destinations qui pourraient vous séduire', AFMM_TERMS) ?></h2>
    <div class="row">
        <?php foreach ($alternate_destination_posts as $destination_post) : ?>
            <?php
            $title_destination = $destination_post->post_title;
            $link_destination = get_permalink($destination_post->ID);
            $id_destination = $destination_post->ID;
            $image_html = get_the_post_thumbnail($id_destination, 'medium', ["class" => "post_image"]);
            $price_db =  get_post_meta($id_destination, 'lowestPrice', true);
            $price = $price_db == '' ? '-' : $price_db;
            $title2_destination = __("Vol à partir de ",AFMM_TERMS)  . $price . "&euro; " . __("A/R*",AFMM_TERMS);
            $preview_type = 'destination';
            $destination_code = get_post_meta($id_destination,'origin_code',true);
            $destination_vol_url = generate_vol_url('PAR',$destination_code) . '&' . generate_utm_params('Vignette');
            ?>
            <div class="col-md-4 post">
                <?php include STYLESHEET_DIR . '/template-parts/country/preview_destination_post.php'; ?>
            </div>
        <?php endforeach ?>
    </div>

<?php endif ?>