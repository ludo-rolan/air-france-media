<?php
global $site_config, $posts_exclude;
$is_white ? $white = "-envol" : $white = '';
$bloc == 'tg' ? $is_gutter = 'no-gutters' : $is_gutter = '';
$offset = $bloc !== 'tg' ? 1 : 0;
$hp_show_category_posts_args = ['ids' => $ids, 'max_posts_number' => $max_posts_number, 'offset' => $offset];
$args = apply_filters('hp_show_category_posts', $hp_show_category_posts_args, $bloc);
$cache_key = $site_config['afmm_cache']['posts_preview_elem']['key'].$bloc;
$cache_time = $site_config['afmm_cache']['posts_preview_elem']['time'];
$query = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function() use ($args){
    return new WP_Query( $args );
});
if (isset($query->posts[0])) {
	$ids = !is_object($query->posts[0]) ? $query->posts[0] : $query->posts[0]->ID;
	$posts_exclude[] = $ids;
}
?>
<div class="row ">
    <?php
    $query = new WP_Query($args);
    $i = 0;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            if ($bloc == "tg" && $i == 0) {
                $query->the_post();
                $link = get_permalink();
                $category_detail = get_the_category(get_the_ID());
    ?>
                <div class="col-md-8 text-center hp_alune">
                    <?php include STYLESHEET_DIR . '/template-parts/home/post_preview_high.php'; ?>
                </div>
                <div class="<?php echo $is_gutter; ?> col-md-4 row">
                <?php

            } else {
                $query->the_post();
                ?>
                    <div class=" <?php echo $is_gutter; ?> col-md-<?php echo $col; ?> post<?php echo $white; ?>  ">
                        <?php include STYLESHEET_DIR . '/template-parts/home/post_preview_elem.php'; ?>
                    </div>
                    <?php
                    if ($bloc == "gouts" && $i == 1) {
                    ?>
                        <div class="col-md-<?php echo $col; ?> newsletter_container ">
                            <?php echo do_shortcode('[shortcode_newsletter]'); ?>
                        </div>

                    <?php
                    }
                }

                $i++;
            }
        }
        if ($bloc == "tg") {

            ?>
                </div>

            <?php
        }


            ?>
</div>
</div>