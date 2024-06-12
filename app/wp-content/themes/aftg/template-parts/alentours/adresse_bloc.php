<?php global $site_config; if ($adresses) { ?>
    <h2 class="destination_title"><?php _e('nos adresses à  ', AFMM_TERMS);
                                    echo $alentour_name; ?></h2>
        <?php include(locate_template('template-parts/components/subnav.php')) ?>
        <div class="row">
            <?php foreach ($adresses as $adresse) { ?>
                <?php
                $id_destination = $adresse->ID;
                $link_destination = get_post_permalink($id_destination);
                $topics = get_the_terms($adresses[$i]->ID,'post_tag');
                $title_destination = join(' - ', wp_list_pluck($topics, 'name'));
	            $image_dimensions = apply_filters('select_cropped_img', ['custom_size' => 'thumb-w-141-h-101', 'original_size' => 'medium']);
	            $image_html = get_the_post_thumbnail($id_destination, $image_dimensions, ["class" => "post_image"]);
                $title2_destination = $adresse->post_title;
                ?>
                <div class="col-md-4 post">
                    <a class="carnet_badge post_card_badge" data-id="<?php echo $id_destination ?>">
                        <?php include(locate_template('template-parts/country/preview_destination_post.php')); ?>
                    </a>
                </div>
            <?php } ?>
            <div class="col-md-4 post">
                <?php echo  do_shortcode("[dfp id=". $site_config['dfp-position']['tax_aux_alentours']['ad2'] ."]") ;?>
            </div>
        </div>
<?php } ?>