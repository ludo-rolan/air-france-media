<?php
$term = get_queried_object();
$post_id_mea = get_option("category_$term->term_id");
$post = get_post($post_id_mea);
$post_author = $post->post_author;
setlocale(LC_TIME, "fr_FR");
$post_date = get_the_date('l j F Y');
$post_title = $post->post_title;
$post_cat_name = get_category($post->post_category[0])->name;
$post_excerpt = $post->post_excerpt;
$post_content = $post->post_content;
$post_url = get_permalink($post->ID);
$recent_author = get_user_by('ID', $post_author);
$author_display_name = $recent_author->display_name;
$video_mea = get_term_meta($term->term_id);
$video_mea_id = '';
$video_mea_type = '';
if(!empty($video_mea)){
    if(!empty($video_mea['_vimeo_id'])){
        $video_mea_id = $video_mea['_vimeo_id'][0];
        $video_mea_type = 'vimeo';
    }
    if(!empty($video_mea['_youtube_id']) && empty($video_mea_id) ){
        $video_mea_id = $video_mea['_youtube_id'][0];
        $video_mea_type = 'youtube';
    }
}
if (has_post_thumbnail($post->ID)) $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
?>
<div class="post-mea container">
    <span id="mobile-element"></span>
    <div class="row d-flex flex-wrap">
       
        <div class="col-lg-6 col-sm-12 video-mea order-lg-2">
            <div class="featured-video-container" style="<?php echo isset($image[0]) && empty($video_mea_id) ? 'background-image: url(' . $image[0] . ')' :  ''; ?>">
                <?php 
                    if(!empty($video_mea_id)){  
                ?>
                <div class="featured-video-container-mea">
                    <div id="category-video-mea">
                        <div class="plyr__video-embed " data-plyr-provider="<?php echo $video_mea_type ?>" data-plyr-embed-id="<?php echo $video_mea_id ?>" id="player"></div>
                    </div>
                </div>
                <?php
                
                }
                ?>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 text-mea my-auto order-lg-1">
           <div class="post-mea-info">
                <div class="post-mea-cat"><?php echo $post_cat_name ?></div>
                <h2 class="post-mea-title"><?php echo $post_title ?></h2>
                <div class="post-mea-date"><?php echo $post_date ?> - <span class="author-mea"><?php echo $author_display_name ?> </span></div>
                <h3 class="post-mea-excerpt"><?php echo wp_trim_words($post_excerpt,10) ?></h3>
                <div class="post-mea-content"><?php echo  wp_trim_words($post_content,20) ?> </div>
                <a id="read-more-btn-mea" href="<?php echo $post_url; ?>"><?php _e("LIRE LA SUITE",AFMM_TERMS) ?></a>
            </div>
        </div>
    </div>
</div>