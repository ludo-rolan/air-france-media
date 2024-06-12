<?php
global $post;
# Get occurence of called shortcode 
$strings = explode('_', $shortcode_name);
$i = end($strings);
$post_address_carte_meta = maybe_unserialize(get_post_meta((int) $post->ID, 'carte_addr_' . $i, true));
if (!empty($post_address_carte_meta)) {
    $titre = !empty($post_address_carte_meta['carte_addr_' . $i.'title_carte']) ? $post_address_carte_meta['carte_addr_' . $i.'title_carte'] : '';
    $address_carte = !empty($post_address_carte_meta['carte_addr_' . $i.'adr_carte']) ? $post_address_carte_meta['carte_addr_' . $i.'adr_carte'] : '';
?>
<div class="address_carte">
    <div class="address_carte-block">
        <img class="address_carte-map-marker" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/pin_carte.png' ?>" alt="location-pointer">
        <div class="address_carte-titre"><?php echo $titre ?></div>
        <div class="address_carte-content"><?php echo $address_carte ?></div>
    </div>
</div>
<?php } ?>