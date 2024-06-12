<?php
global $site_config;
if (is_front_page()) {
    get_header('home');
    $is_ipad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
    if(!$site_config['ismobile'] && !$is_ipad){
    get_header();
    }
} else {
    get_header();
}




global $option_hp;
$option_hp = get_option(ICL_LANGUAGE_CODE.'_'.'hp_options');
$cache_key = $site_config['afmm_cache']['hp_hero']['key'];
$cache_time = $site_config['afmm_cache']['hp_hero']['time'];
$query = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function() use ($option_hp){
    return new WP_Query(array('orderby' => 'post__in', 'post__in' => explode(',', $option_hp[apply_filters('get_fields_deps_lang','hp_options_post_bandeau')])));
});

$posts_exclude = [$query->posts[0]->ID];
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $cache_key = $site_config['afmm_cache']['hp_category_alune']['key'];
        $cache_time = $site_config['afmm_cache']['hp_category_alune']['time'];
        $category_detail = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function(){
            return get_the_category(get_the_ID());
         });
        $link = get_permalink();
        $fullwidth_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>
        <div class="hp_bandeau_image img-responsive responsive--full parallax--bg" style="background-image:url(' <?php echo $fullwidth_img; ?>')"></div>
        <section class="container">
            <div class="hp_bandeau row ">
                <span class="hp_bandeau_cat">
                    <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
                        <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
                    </a>
                </span>
                <h1 class="hp_bandeau_title">
                    <a href="<?php echo $link ?>"><?php echo get_the_title() ?> </a>
                </h1>
                <span class="hp_bandeau_info">
                    <?php  echo get_the_date('l j F Y')." - " ;
                     the_author()  ?>
                </span>
                <a href="<?php echo $link ?>" class="hp_bandeau_btn"><?php echo __('lire la suite', AFMM_TERMS); ?></a>
            </div>
        </section>
<?php
    }
}
/* Restore original Post Data */
wp_reset_postdata();
?>
<?php
    $hp_show_category_posts_args = ['ids' => $option_hp[apply_filters('get_fields_deps_lang','hp_options_post_alune')], 'max_posts_number' => 1];
    $args = apply_filters('hp_show_category_posts', $hp_show_category_posts_args, 'a-la-une');
    $cache_key = $site_config['afmm_cache']['hp_post_alune']['key'];
    $cache_time = $site_config['afmm_cache']['hp_post_alune']['time'];
    $query = get_data_from_cache($cache_key ,'hp_locking_ids',$cache_time,function() use ($args){
        return new WP_Query( $args );
    });
if (isset($query->posts[0]) && !empty($query->posts[0]->ID)) {
	$posts_exclude[] = $query->posts[0]->ID;
}

    include(locate_template('template-parts/block_post_a_la_une.php'));
?>
<section class="container" >
<?php 
 echo do_shortcode('[post_preview_elem bloc="a-la-une" max_posts_number=3 col=4 ids=' . $option_hp[apply_filters('get_fields_deps_lang','hp_options_trois_post_alune')] . ']');
?>

<?php echo do_shortcode("[dfp id='native']") ?>


</section>
<section class="container" >

<?php
echo do_shortcode("[category_name label='inspirations' ]");
$ids=$option_hp[apply_filters('get_fields_deps_lang','hp_options_quatre_post_inspiration')];
include(locate_template('template-parts/home/bloc_inspiration.php'));
?>
</section>
<?php
//include(STYLESHEET_DIR . '/template-parts/home/podcast_block.php');
include(STYLESHEET_DIR . '/template-parts/home/ville_block.php');

?>


<section class="container" >

<?php
$key_option = apply_filters('get_fields_deps_lang','hp_options_post_evasion');
$key_posts = apply_filters('get_fields_deps_lang','hp_options_trois_post_evasion');
$category = "evasion";
echo
do_shortcode('[post_preview_wide max_posts_number=3 key_option=' . $key_option . ' key_posts=' . $key_posts . ' category=' . $category . '  ]');
?>
</section>
<?php
include(STYLESHEET_DIR . '/template-parts/home/video_block.php');
?>
<section class="container" >

<?php
$key_option = apply_filters('get_fields_deps_lang','hp_options_post_styles');
$key_posts = apply_filters('get_fields_deps_lang','hp_options_trois_post_styles');
$category = "styles";
echo
do_shortcode('[post_preview_wide max_posts_number=3 key_option=' . $key_option . ' key_posts=' . $key_posts . ' category=' . $category . '  ]');
?>
</section>


<?php do_action('hp_after_block_styles');  ?>

<section class="container" >
<?php
echo do_shortcode("[category_name label='goût' ]");
echo do_shortcode('[post_preview_elem bloc="gouts" max_posts_number=2 col=4]');
?>
</section>

<section class="container-fluid mt-4" style="background-color: #061132;">
    <div class="container a-bord">
    <?php
    echo do_shortcode("[category_name is_white=1 label='a-bord' ]");
    echo do_shortcode('[post_preview_elem bloc="a-bord" max_posts_number=3 is_white=1  col=4 ids=' . $option_hp[apply_filters('get_fields_deps_lang','hp_options_trois_post_a_bord')] . ']');
    ?>
    </div>
</section>
<section class="container" >

<?php
echo do_shortcode("[category_name label='travel-guide' ]");
echo do_shortcode('[post_preview_elem bloc="tg" max_posts_number=3 col=12 ids=' . $option_hp[apply_filters('get_fields_deps_lang','hp_options_trois_post_tg')] . ']');
?>
<a href="" class="hp_more "><?php _e('LIRE PLUS DE GUIDES', AFMM_TERMS); ?></a>
</section>

<?php
get_footer();
