<?php global $site_config; if ($alentour_map) { ?>
    <h2 class="destination_title"><?php _e('carte de ', AFMM_TERMS);
                                    echo $alentour_name; ?></h2>
    <div class="row">
        <div class="col-md-8">
            <div id="map-container" class="destination-map"></div>
        </div>
        <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['tax_aux_alentours']['ad3'] ."]") ;?>
    </div>
<?php } ?>