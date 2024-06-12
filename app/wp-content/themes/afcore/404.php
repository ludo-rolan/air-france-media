<?php 
global $site_config;
get_header(); 
?>
<div class="container-fluid error404_top">
    
    <div class="row error404_content">
    <div class="error404_fog" id="fog"></div>
            <div class="error404_wrapper">

                <span class="error404_title"><?php  _e("erreur 404", AFMM_TERMS) ?></span>
            </div>
            <p class="error404_msg"><?php  _e("on dirait que vous êtes partis un peu trop Loin...", AFMM_TERMS) ?></p> 
            <p class="error404_msg"><?php  _e("besoin d'aide pour retrouver votre chemin ?", AFMM_TERMS) ?></p>
            <a class="btn error404_btn" href="<?php echo $site_config['site_url']; ?>"><?php  _e("Retour à la page d'accueil", AFMM_TERMS) ?></a>

    </div><!-- .error404_content -->

</div><!-- .error404_top -->
    
    <?php
    $type=(PROJECT_NAME=="afmm" ? 'post' : "adresse");
     $query_posts = new WP_Query( 
         array(
            'posts_per_page' => 3,
            'post_type'=> $type ));
    $type=($type=="post" ? "article" : $type);
    ?>
    
<div class="container error404_articles_bottom">
    <div class="row error404_articles_title">
        <h1><?php  _e("Ces ".$type."s pourraient vous intéresser", AFMM_TERMS) ?>
    </div>
    <div class="row error404_articles_wrapper">
    <?php
    if ($query_posts->have_posts()) {
        while ($query_posts->have_posts()) {
                $query_posts->the_post();
                $link = get_permalink();
                $category_detail = get_the_category(get_the_ID());
                ?>
                    <div class="col-md-4 post error404_articles">
                        <?php include STYLESHEET_DIR . '/template-parts/home/post_preview_elem.php'; ?>
                    </div>
                <?php }
    }
                ?>
    </div><!--error404_articles_wrapper-->
</div><!--error404_articles_bottom-->
<?php
get_footer();
