<?php
 global $post;
 $post_slug = $post->post_name;

 $cache_key_posts = $site_config['aftg_cache']['maillage_afmm_posts']['key']. '_' . $post->ID;
 $cache_time_posts = $site_config['aftg_cache']['maillage_afmm_posts']['time'];
 $cache_key_id = $site_config['aftg_cache']['destination_external_id']['key']. '_' . $post->ID;
 $cache_time_id = $site_config['aftg_cache']['destination_external_id']['time'];
 $args=['slug'=>$post_slug];
 $external_post_id  = get_data_from_cache($cache_key_id.'_'.$post_slug,'aftg_maillage_id',$cache_time_id,function() use ($args){
    return  Maillage::aftg_maillage_slug_external_id($args['slug']);
 }); 
 $args['id']=$external_post_id;
 $afmm_posts  = get_data_from_cache($cache_key_posts.'_'.$post_slug,'aftg_maillage_posts',$cache_time_posts,function() use ($args){
     return  Maillage::aftg_maillage_article_par_destination($args['id']);
  }); 
?>
<?php if(count($afmm_posts)){?>
    <h2 class="destination_title"><?php _e('plus d\'articles sur ', AFMM_TERMS);echo $post_title; ?></h2>
<?php } ?>
<div class="row">
    <!-- statique en attandant le maillage -->
    <?php
    if (count($afmm_posts)) {
        $offset="";
        count($afmm_posts)==2 ? $offset='<div class="col-md-2"></div>': "";
        count($afmm_posts)==1 ? $offset='<div class="col-md-4"></div>': "";
        echo $offset;
        ?>
        <?php foreach($afmm_posts as $post) { ?>
            <?php
            $thumbnail=isset($post['_embedded']['wp:featuredmedia']['0']['media_details']['sizes']['medium']['source_url'])?$post['_embedded']['wp:featuredmedia']['0']['media_details']['sizes']['medium']['source_url']:''  ;
            $afmm_link =  isset($post['link']) ?$post['link']:'';
            $afmm_category = isset($post['_embedded']['wp:term'][0][0]["name"])?$post['_embedded']['wp:term'][0][0]["name"]:'';
            $afmm_category_link=isset($post['_embedded']['wp:term'][0][0]["link"])?$post['_embedded']['wp:term'][0][0]["link"]:'';
            $afmm_title = isset($post['title']['rendered'])?$post['title']['rendered']:"";
            ?>
            <div class="col-md-4 post">
                <?php include STYLESHEET_DIR . '/template-parts/country/preview_afmm_post.php'; ?>
            </div>
    <?php }

    } ?>
</div>
