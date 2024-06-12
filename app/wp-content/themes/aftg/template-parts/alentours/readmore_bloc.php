<?php
$cache_key_posts = $site_config['aftg_cache']['readmore_aftg_bloc']['key'] . '_' . $current_term->term_id;
$cache_time_posts = $site_config['aftg_cache']['readmore_aftg_bloc']['time'];

$afmm_posts  = get_data_from_cache($cache_key_posts . '_' . $post_slug, 'readmore_aftg_bloc', $cache_time_posts, function () {
    return  Maillage::aftg_maillage_article_recents();
});
?>

<?php if (count($afmm_posts)) { ?>
    <h2 class="destination_title"><?php _e('à lire aussi', AFMM_TERMS) ?></h2>
<?php } ?>
<div class="row">
    <?php
    if (count($afmm_posts)) {
    ?>
        <?php foreach ($afmm_posts as $post) { ?>
            <?php
            $thumbnail = isset($post['_embedded']['wp:featuredmedia']['0']['source_url']) ? $post['_embedded']['wp:featuredmedia']['0']['source_url'] : '';
            $afmm_link =  isset($post['link']) ? $post['link'] : '';
            $afmm_category = isset($post['_embedded']['wp:term'][0][0]["name"]) ? $post['_embedded']['wp:term'][0][0]["name"] : '';
            $afmm_category_link = isset($post['_embedded']['wp:term'][0][0]["link"]) ? $post['_embedded']['wp:term'][0][0]["link"] : '';
            $afmm_title = isset($post['title']['rendered']) ? $post['title']['rendered'] : "";
            ?>
            <div class="col-md-4 post">
                <?php include(locate_template('template-parts/country/preview_afmm_post.php')); ?>
            </div>
    <?php }
    } ?>
</div>