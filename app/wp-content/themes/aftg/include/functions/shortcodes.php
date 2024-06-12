<?php

function register_shortcodes() {
    add_shortcode( 'visiter_endroit', 'shortcode_visiter_endroit' );
    add_shortcode( 'article_plus_lus', 'shortcode_article_plus_lus' );
    add_shortcode( 'meteo_table', 'shortcode_meteo_table');
    for ($i = 1; $i <= 10; $i++) {
        add_shortcode('carte_addr_'.$i, 'shortcode_carte_address');
    }
    add_shortcode( 'bon_a_savoir', 'shortcode_bon_a_savoir' );
    add_shortcode( 'block_essentialPhrases', 'shortcode_block_essentialPhrases' );
    add_shortcode( 'timelapse', 'render_iframe');
}

add_action( 'init', 'register_shortcodes' );
add_shortcode( 'af_lang_audio', 'shortcode_lang_audio' );
function shortcode_carte_address($user_defined_attributes, $content, $shortcode_name ) {
    $attributes = shortcode_atts(
        array(),
        $user_defined_attributes,
        $shortcode_name
    );
    ob_start();
    include(locate_template("template-parts/bloc_carte_address.php"));
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
function shortcode_bon_a_savoir(){

    ob_start();
    include(locate_template('template-parts/block_bon_a_savoir.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function shortcode_lang_audio($atts){
    $atts = shortcode_atts( array('id' =>'',), $atts);
    $id=$atts['id'];
    if ( $id == '') return '';
    ob_start();
    include(locate_template('template-parts/shortcode_lang_audio.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function shortcode_block_essentialPhrases(){
    ob_start();
    include(locate_template('template-parts/shortcode_block_essential_phrases.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function shortcode_meteo_table(){
    ob_start();
    include(locate_template("template-parts/bloc_meteo_table.php"));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function render_iframe($atts){
    ob_start();
    // vaut mieu de changer lite vimeo par iframe
    // psk iframe donne plus de controlle que lite vimeo
    if($id=$atts['id']){
        $video_source = "https://player.vimeo.com/video/$id?&autoplay=1&loop=1&muted=1&controls=0";
    ?>
    <div class="destination_video">
        <div class="destination_video_container">
            <iframe loading="lazy" width="100%" height="720"
                <?php 
                    echo "src=".$video_source. 'frameborder="0" scrolling="no" allow="autoplay ; fullscreen" allowfullscreen webkitallowfullscreen mozallowfullscreen';
                ?>
            ></iframe>
        </div>
    </div>
    <?php
    }
    return ob_get_clean();
}

