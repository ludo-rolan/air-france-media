<?php
/**
 * Template Name: Listing Destination
 */
get_header();
?>
<section class="page-listing-destination">
<?php echo do_shortcode("[dfp id=". $site_config['dfp-position']['lesting-destination']['ad1'] ."]") ;?>
<?php get_breadcrumb(); ?>

	<?php
        $select_title = '';
	    $is_modal_content = false;
	    $is_content_clickable = true;
        include( locate_template( 'template-parts/modal-content/select_destination.php' ));
    ?>
</section>

<?php
get_footer();