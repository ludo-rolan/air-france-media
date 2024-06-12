<?php 
global $site_config;
get_header();
?>
<div class="crumbs-nospace">
<?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['homepage']['ad1'] ."]") ; ?>
<div class="pt-5">
<?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['homepage']['mobile_ad1'] ."]") ; ?>
</div>
    <?php get_breadcrumb(); ?>
</div>
<?php 
include(locate_template('template-parts/home/destinations_block.php'));

get_footer();
