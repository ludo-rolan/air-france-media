<?php
global $post;
# Get occurence of called shortcode 
$i = end(explode('_', $shortcode_name));

$post_via_fretta_meta = maybe_unserialize(get_post_meta((int) $post->ID, 'via_fretta_'.$i, true));
if (!empty($post_via_fretta_meta)) {
    $titre = $post_via_fretta_meta['via_fretta_'.$i.'titre'];
    $latitude = $post_via_fretta_meta['via_fretta_'.$i.'latitude'];
    $longitude = $post_via_fretta_meta['via_fretta_'.$i.'longitude'];
    $altitude = $post_via_fretta_meta['via_fretta_'.$i.'altitude'];
    $aeroport_plus_proche = $post_via_fretta_meta['via_fretta_'.$i.'aeroport_plus_proche'];
    $adresse = $post_via_fretta_meta['via_fretta_'.$i.'adresse'];
    $ville = $post_via_fretta_meta['via_fretta_'.$i.'ville'];
    $pays = $post_via_fretta_meta['via_fretta_'.$i.'pays'];    
?>
<div class="via-fretta">
    <div class="via-fretta-block">
        <img class="via-fretta-map-marker" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/location-pointer.svg' ?>" alt="location-pointer">
        <div class="via-fretta-titre"><?php echo $titre ?></div>
        <ul class="via-fretta-coordonnees">
            <?php if (!empty($latitude)){ ?><li><span class="via-fretta-attr"><?php _e("Latitude :",AFMM_TERMS) ?></span> <?php echo $latitude ?></li><?php } ?>
            <?php if (!empty($longitude)){ ?><li><span class="via-fretta-attr"><?php _e("Longitude :",AFMM_TERMS) ?></span> <?php echo $longitude ?></li><?php } ?>
            <?php if (!empty($altitude)){ ?><li><span class="via-fretta-attr"><?php _e("Altitude :",AFMM_TERMS) ?></span> <?php echo $altitude ?></li><?php } ?>
            <?php if (!empty($adresse)){ ?><li><span class="via-fretta-attr"><?php _e("Adresse :",AFMM_TERMS) ?></span> <?php echo $adresse ?></li><?php } ?>
            <?php if (!empty($ville)){ ?><li><span class="via-fretta-attr"><?php _e("Ville :",AFMM_TERMS) ?></span> <?php echo $ville ?></li><?php } ?>
            <?php if (!empty($pays)){ ?><li><span class="via-fretta-attr"><?php _e("Pays :",AFMM_TERMS) ?></span> <?php echo $pays ?></li><?php } ?>
            <?php if (!empty($aeroport_plus_proche)){ ?><li><span class="via-fretta-attr"><?php _e("Aéroport le plus proche :",AFMM_TERMS) ?></span> <?php echo $aeroport_plus_proche ?></li><?php } ?>
        </ul>
    </div>
</div>
<?php } ?>