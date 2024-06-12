<?php 

function register_af_shortcodes() {
    add_shortcode( 'instagram_embed', 'shortcode_embed_instagram' );
    add_shortcode('facebook_embed','shortcode_embed_facebook');
    add_shortcode( 'af_video', 'shortcode_player' );
}

add_action( 'init', 'register_af_shortcodes' );

/* Shortcode to embed an instagram post, it takes the ID of the post as an argument.
The shortcode template file contains the embed code provided by instagram to show an iframe of the post
For more infos : https://about.instagram.com/blog/announcements/introducing-web-embedding-instagram-content-on-websites
*/
function shortcode_embed_instagram($atts){
    $atts = shortcode_atts( array('id' =>'',), $atts);
    $id=$atts['id'];
    if ( $id == '') return '';
    ob_start();
    include(locate_template('template-parts/shortcode_embed_instagram.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

// pour partager des posts facebook (images, videos ...)
function shortcode_embed_facebook($atts){
    $atts = shortcode_atts( array('id' =>'',), $atts);
    $id=$atts['id'];
    if ( $id == '') return '';
    ob_start();
    include(locate_template('template-parts/shortcode_embed_facebook.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function shortcode_player($attrs){
    //DOC: test arguments for the player
    //	$attrs = array(
    //		"src" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4",
    //		"vimeo_id" => "586843140",
    //		"youtube_id" => "We__CnrYa9U",
    //      "dailymotion_id" => "x4vvl7c",
    //		"autoplay" => "true",
    //		"poster" => "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Big_Buck_Bunny_thumbnail_vlc.png/1200px-Big_Buck_Bunny_thumbnail_vlc.png",
    //	);
    $attrs = shortcode_atts(
        array(
            "src" => "",
            "vimeo_id" => "",
            "youtube_id" => "",
            "dailymotion_id" => "",
            "autoplay" => "false",
            "poster" => "",
        ),
        $attrs
    );
    ob_start();
    include(locate_template('/template-parts/shortcode_player.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}