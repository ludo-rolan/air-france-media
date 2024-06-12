<?php
global $post;
$post_type = $post->post_type;
if ($post_type == 'travel-guide') {
?>
    <div class="tg-bandeau">
        <h2 class="tg-bandeau_name"> <?php _e("Travel guide ",AFMM_TERMS) ?></h2>
    </div>
<?php } ?>