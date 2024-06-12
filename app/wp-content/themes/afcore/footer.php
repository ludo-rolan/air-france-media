<?php global $site_config;?>
        <div class="container ">
            <?php do_action('footer_logo'); ?>
        </div>

<footer>
    <div class=" bandeau">
        <div class="  row container ">
            

            <div class=" bandeau_logo col-lg-5 text-center">
                <img loading="lazy" src="<?php echo $site_config['logo_footer'] ?>" alt="Afmm">
            </div>
            <div class="col-lg-3 pt-4">
                <h4 class="bandeau_name"><?php _e("SUIVEZ-NOUS",AFMM_TERMS) ?></h4>

            </div>
            <div class="col-lg-3 pt-4">
                <?php Maillage::get_afmm_menu("footer_1"); ?>
            </div>
        </div>
    </div>

    <?php Maillage::get_afmm_menu("footer_2"); ?>
    <?php Maillage::get_afmm_menu("footer_3");?>

</footer>


<script>

</script>
<?php wp_footer(); ?>
</body>

</html>