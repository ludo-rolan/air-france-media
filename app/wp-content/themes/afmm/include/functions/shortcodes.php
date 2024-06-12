<?php

function register_shortcodes() {
    add_shortcode( 'post_preview_elem', 'post_preview_elem' );
    add_shortcode( 'category_name', 'category_name' );
    add_shortcode( 'post_preview_wide', 'post_preview_wide' );
    add_shortcode('shortcode_newsletter','shortcode_newsletter');
    add_shortcode( 'avions_infos', 'shortcode_display_avions');
    add_shortcode( 'instagram', 'shortcode_instagram' );
    add_shortcode( 'contact', 'shortcode_contact' );
    add_shortcode( 'visiter_endroit', 'shortcode_visiter_endroit' );
    add_shortcode( 'article_plus_lus', 'shortcode_article_plus_lus' );
    add_shortcode('page_newsletter', 'shortcode_page_newsletter');
    for ($i = 1; $i <= 10; $i++) {
        add_shortcode('pinned_address_'.$i, 'shortcode_pinned_address');
    }
}

add_action( 'init', 'register_shortcodes' );
function post_preview_elem($atts){
    ob_start();
    $ids = $atts['ids'] ?? false;
    $col = $atts['col'] ?? false;
    $bloc = $atts['bloc'] ?? false;
    $is_white = $atts['is_white'] ?? false;
    $max_posts_number = $atts['max_posts_number'] ?? false;
    include(locate_template('template-parts/home/posts_preview.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function post_preview_wide($atts){

    ob_start();
    $key_option = $atts['key_option'];
    $key_option_posts = $atts['key_posts'];
    $name_category = $atts['category'];
	$max_posts_number = $atts['max_posts_number'] ?? false;
    include (locate_template('template-parts/home/post_preview_wide.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function category_name($atts){

    ob_start();
    $category_slug=$atts['label'];
    $is_white=isset($atts['is_white']) ? $atts['is_white'] : false;
    include(locate_template('template-parts/home/category_header.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;

}

function shortcode_newsletter($atts){

    ob_start();
    include(locate_template('template-parts/newsletter.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;

}
function shortcode_display_avions($atts){
    ob_start();
    if(isset($atts['ids'])){
        $ids=$atts['ids'];
    }
    include(locate_template('template-parts/avion/shortcode_display_avions.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function shortcode_instagram($atts){
    $atts = shortcode_atts( array('id' =>'',), $atts);
    $id=$atts['id'];
    if ( $id == '') return '';
    ob_start();
    include(locate_template('template-parts/shortcode_instagram.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function shortcode_contact(){
    ob_start();
    include(locate_template('template-parts/shortcode-contact.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function shortcode_visiter_endroit(){
    ob_start();

    include(locate_template("template-parts/block_visiter_endroit.php"));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function shortcode_article_plus_lus(){

    ob_start();
    include(locate_template('template-parts/article_plus_lus.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function shortcode_page_newsletter(){
    ob_start();
    include( locate_template('template-parts/forms/newsletter_html.php') );
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function shortcode_pinned_address($user_defined_attributes, $content, $shortcode_name ) {
    $attributes = shortcode_atts(
        array(),
        $user_defined_attributes,
        $shortcode_name
    );
    ob_start();
    include(locate_template("template-parts/block_via_fretta.php"));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
