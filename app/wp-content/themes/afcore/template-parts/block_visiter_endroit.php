<?php
global $post;
global $site_config;
$post_visiter_endroit_meta = maybe_unserialize(get_post_meta((int) $post->ID,'visitez_endroit',true));
$hasText= ( !empty($post_visiter_endroit_meta['visitez_endroit_titre']) &&  !empty($post_visiter_endroit_meta['visitez_endroit_discription']) );
?>
<div class="container visitez_endroit <?php echo !($hasText) ? "horizontal" : ""; ?>">
    <div class="row">
        <div <?php echo ($hasText) ? "class='col-lg-8 visitez_endroit_txt'" : "class='visitez_endroit_txt'"; ?>  >

            <h2 class="visitez_endroit_titre"><mark class="visitez_endroit_titre_mark"><?php echo $post_visiter_endroit_meta['visitez_endroit_titre'] ?></mark></h2>
            <?php $description = apply_filters('the_content', $post_visiter_endroit_meta['visitez_endroit_discription'] ); ?>
            <div class="visitez_endroit_discription"><?php echo $description; ?></div>
        </div>
        
        
        <div class="visitez_endroit_visuel <?php echo ($hasText) ? 'col-lg-4' : 'col-lg-12 '; ?>"> 
                <div class="visitez_endroit_visuel_expandable-<?php echo ($hasText) ? 'mapRight' : 'mapBottom'; ?>"
                    style="background-image:url('<?php 
                         echo ($hasText) ? STYLESHEET_DIR_URI . '/assets/img/mapRedFlat.png' : STYLESHEET_DIR_URI . '/assets/img/'.PROJECT_NAME.'_mapFlat_hor.png';
                    ?>')">
                </div>
            
            <?php
            // vignette 1 
                render_vignette_html($post_visiter_endroit_meta,'visitez_endroit_vignette1','visitez_endroit_bg_color_vignette1','visitez_endroit_svg_vig1','visitez_endroit_titre_vignette1', 'visitez_endroit_lien_vignette1',$hasText,'DESTINATION'); 
            // vignette 2 
                render_vignette_html($post_visiter_endroit_meta,'visitez_endroit_vignette2','visitez_endroit_bg_color_vignette2','visitez_endroit_svg_vig2','visitez_endroit_titre_vignette2', 'visitez_endroit_lien_vignette2',$hasText,'ENVIES');
            //  vignette 3 
                if($post_visiter_endroit_meta['visitez_endroit_custom_link'] == 'true')
                {
                    $visitez_endroit_lien_vignette3 = $post_visiter_endroit_meta['visitez_endroit_lien_vignette3'];
                }else{
                    $visitez_endroit_lien_vignette3 = $site_config['visitez_endroit']['reservation_site'] . '&'. generate_utm_params('SquareButton','RDH');
                }
            ?>
                <div class="visitez_endroit_visuel_vignette" style="background-color:<?php echo $site_config['visitez_endroit']['bg_color_vignette'] ?>">
                    <a id='rdh_media_maillage'  class="visitez_endroit_visuel_vignette_content"
                        href="<?php echo $visitez_endroit_lien_vignette3 ?>"
                        target="_blank"
                    >
                        <img src="<?php echo $site_config['visitez_endroit']['image_vignette'] ?>"/>  
                        <p> <?php echo $site_config['visitez_endroit']['titre_vignette'];?> </p>
                    </a>
                </div>

        </div>
    </div>
</div>


