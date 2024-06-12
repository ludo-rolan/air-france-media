<?php 
global $site_config;
get_header();
?>
<div class="container">
<?php get_breadcrumb(); ?>
    <div class="row">
        <div id="content"class="col-12">
            <?php 
                while (have_posts()) {
                    the_post();
            ?>

                <div class="page-content"><?php the_content(); ?></div>                              

            <?php } ?>
        </div>
    </div>
</div>
<?php
get_footer();