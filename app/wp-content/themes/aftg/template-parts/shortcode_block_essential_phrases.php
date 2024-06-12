<?php 
$essential_phrases_meta = get_post_meta(get_the_ID(), 'essentialPhrases', true);
$title = get_post_meta(get_the_ID(), 'titles', true)['titles_essentialPhrases'];
$current_lang_phrases = $essential_phrases_meta['essentialPhrases_fr'];
if ( function_exists('icl_object_id') ) {
    $langs = apply_filters('wpml_active_languages', NULL);
    $current_lang_phrases = $essential_phrases_meta['essentialPhrases_'.ICL_LANGUAGE_CODE];
}
?>

<h2 class="infosPratiques_title"><?php echo $title; ?></h2>
<div class="infosPratiques_description"><?php echo do_shortcode($current_lang_phrases); ?></div>
