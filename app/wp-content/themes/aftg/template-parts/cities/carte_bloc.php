<?php if($map['map_mapId']!=''){
    $url= add_query_arg( 'language', $site_config['langue_selectionner'], $map['map_mapId'] );
?>
    <div class="bloc" id="carte">
        <h2 class="destination_title"><?php _e('carte de  ', AFMM_TERMS);
                                        echo $post_title; ?></h2>
        <div class="row">
            <!-- c'est la ou on trouve la map -->
            <div id="map-container" class="col-md-8" style="height:600px">
                
            </div>
            <div class="col-md-4">
                <?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['single_destination']['ad4'] ."]") ; ?>
            </div>
        </div>
    </div>
<?php } ?>