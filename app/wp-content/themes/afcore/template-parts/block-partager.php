<?php
global $post;
global $site_config;

$post_id = $post->ID;
$post_type = $post->post_type;
$article_type = get_post_meta((int) $post_id, 'post_display_type_name', true);
$is_edito = ($article_type == 'EDITO' || $article_type == 'DIAPO');

$partagerURL = urlencode(get_permalink());
$partagerTitle = htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
$partagerThumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
$pinterestThumbnail =  $partagerThumbnail[0] ?? STYLESHEET_DIR_URI . '/assets/img/mapRedFlat.png';

$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $partagerURL;
$twitterURL = 'https://twitter.com/intent/tweet?text=' . $partagerTitle . '&amp;url=' . $partagerURL . '&amp;via=partager';
$pinterestURL = 'https://pinterest.com/pin/create/button/?url=' . $partagerURL . '&amp;media=' . $pinterestThumbnail . '&amp;description=' . $partagerTitle;
$whatsappURL = 'https://api.whatsapp.com/send?text=' . $partagerTitle . ' ' . $partagerURL;
$linkedinURL = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $partagerURL;

$mailSubject = $site_config['mail']['subject'] . ' ' . $partagerTitle;
$mailContent = $site_config['mail']['content'] . ' ' . $partagerURL;
$mailURL = 'https://mail.google.com/mail/?view=cm&&su=' . $mailSubject . '&body=' . $mailContent;

$is_after_content = (current_filter() == 'after_the_content') ? true : false;
?>
<div class="block-partager-social">
    <?php if ($is_after_content) { ?>
        <h5 class="social-share-title-desktop"><?php _e("PARTAGEZ CE CONTENU",AFMM_TERMS) ?></h5>
        <h5 class="social-share-title-mobile"><?php _e("PARTAGER",AFMM_TERMS) ?></h5>
        <div class="btn-partager">
        <?php  } else { ?> <div class="btn-partager-mini <?php echo $is_edito ? "btn-partager-mini-edito" : "" ?>"> <?php } ?>
            <div class="btn-partager-rs">
                <a class="block-partager-link block-partager-facebook fa fa-facebook " href="<?php echo $facebookURL ?>" target="_blank"><?php if ($is_after_content) { ?><span><?php _e("FACEBOOK",AFMM_TERMS) ?></span><?php } ?></a>
                <a class="block-partager-link block-partager-twitter fa fa-twitter" href="<?php echo $twitterURL  ?>" target="_blank"><?php if ($is_after_content) { ?><span><?php _e("TWITTER",AFMM_TERMS) ?></span><?php } ?></a>
                <a class="block-partager-link block-partager-pinterest fa fa-pinterest-p" href=" <?php echo $pinterestURL ?>" data-pin-custom="true" target="_blank"><?php if ($is_after_content) { ?><span><?php _e("PINTEREST",AFMM_TERMS) ?></span><?php } ?></a>
            </div>
            <div class="btn-partager-autres">
                <a class="block-partager-link btn-partager-click  fa fa-plus-circle"  target="_blank"> <?php if ($is_after_content) { ?><span><?php _e(" AUTRES",AFMM_TERMS) ?></span><?php } ?></a>
                <div class="btn-partager-autres-plus  btn-partager-autres-hidden">
                    <a class="block-partager-link block-partager-facebook fa fa-linkedin " href="<?php echo $linkedinURL ?>" target="_blank"><?php if ($is_after_content) { ?><span><?php _e("LINKEDIN",AFMM_TERMS) ?></span><?php } ?></a>
                    <a class="block-partager-link block-partager-twitter fa fa-whatsapp" href="<?php echo $whatsappURL  ?>" target="_blank"><?php if ($is_after_content) { ?><span><?php _e("WHATSAPP",AFMM_TERMS) ?></span><?php } ?></a>
                    <a class="block-partager-link " href=" <?php echo $mailURL ?>"  target="_blank"><img src=<?php echo AF_THEME_DIR_URI . '/assets/img/mail.png' ?> /><?php if ($is_after_content) { ?><span><?php _e("MAIL",AFMM_TERMS) ?></span><?php } ?></a>
                    <a class="block-partager-link copy-btn" data-copy="<?php echo get_the_permalink() ?>" href="javascript:void(0)"><img src=<?php echo AF_THEME_DIR_URI . '/assets/img/link.png' ?> /><?php if ($is_after_content) { ?><span><?php _e("COPIER",AFMM_TERMS) ?></span><?php } ?></a>
                </div>
            </div>
            </div>
            <?php 
            if (!$is_after_content && PROJECT_NAME == 'aftg' && $post_type == "adresse") { ?>
            <div class ="block-carnet">
            <a class="block-partager-link carnet_badge_adresse " id="carnet-id" data-id="<?php echo get_the_ID() ?>" target="_blank"><img src=<?php echo AF_THEME_DIR_URI . '/assets/img/carnet.png' ?> /></a>
            </div>
            <?php } ?>
        </div>
       