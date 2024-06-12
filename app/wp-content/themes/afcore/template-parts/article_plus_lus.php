<h2 class="sidebar_title text-uppercase ml-3"><?php _e('les + lus', AFMM_TERMS) ?></h2>
<?php
global $site_config;
require(AF_THEME_DIR . '/include/ga_popular.php');
$website_id = $site_config['ga_analytics'][PROJECT_NAME.'_'.ICL_LANGUAGE_CODE];
PROJECT_NAME == "afmm" ? $post_type = "post" : $post_type = "adresse";
$cache_key = $site_config['afcore_cache']['most_popular']['key'] . '_' . $post_type . '_' . $website_id;
$cache_time = $site_config['afcore_cache']['most_popular']['time'];
echo get_data_from_cache($cache_key, 'afcore_cache', $cache_time, function () use ($website_id,$post_type) {
    $is_side_bar = true;
    $popular_posts_init = new Ga_popular($website_id, '7daysAgo', 'yesterday', 'ga:pageviews', 'ga:pagePath', '-ga:pageviews', 'gaid::-1', '50', 'ga:pagePath!=/;ga:pagePath=@-;ga:pagePath!@?');
    $query = $popular_posts_init->retrieve_data($post_type);
    foreach ($query as $article) {
        global $post;
        $post = $article;
        $topics = get_the_terms(get_the_ID(),'post_tag');
        PROJECT_NAME != "afmm" ? $adress_tag =  join(' - ', wp_list_pluck($topics, 'name')) : $adress_tag = null ;
        $link = get_permalink();
        $category_detail = get_the_category(get_the_ID());
?>
        <div class="sidebar_plusLus">
            <?php include STYLESHEET_DIR . '/template-parts/home/post_preview_elem.php'; ?>
        </div>
<?php
    }
});
?>