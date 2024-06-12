<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php
global $site_config, $site_config_js;
include(locate_template('template-parts/header/head.php'));

?>


<style>.skin_size_1000  header {margin: 0 auto; max-width: 1000px;background-color: white;margin-top: -33px;}</style>
<style>.skin_size_1000 .post_link {z-index: 100;}</style>
<style>.skin_size_1000 .native-contianer {margin-top:0!important;margin-bottom: 0!important;}</style>
<style>.skin_size_1000 .navbar .navbar-expand-lg {background-color: white;}</style>
<style>.skin_size_1000 .crumbs {background-color: white;max-width: 1000px;margin: 0px auto -8px;padding-top:33px}</style>
<style>.skin_size_1000 .mt-5 {padding-top: 3rem!important;margin-top: 0px !important;}</style>
<style>.skin_size_1000 a.sidebar_plusLus_link {z-index: 1;}</style>
<style>.pub_dfp .habillage .filled-with-pub {max-width: 1000px;margin: 0 auto; background: white;}</style>
<style>.skin_size_1000 #Calque_1 {display: none;}</style>





<body <?php body_class(); ?> style="position:relative; overflow-x: hidden ">
<?php if(PROJECT_NAME == 'afmm')include(locate_template('template-parts/header/line_svg.php')); ?>

    <?php wp_body_open(); ?>
    <header id="homeheader">
        <div id="af-header" class="container d-flex justify-content-between align-items-center py-3">
            <div class="nav-desktop-language">
            <?php     include(locate_template('template-parts/header/lang-selection.php')); ?>
            </div>
            <a class="af-header-logo-centered" href="<?php echo home_url() ?>">
                <img src="<?php echo $site_config['logo_header'] ?>" alt="">
            </a>
            <a class="btn af-btn" href="<?php echo $site_config['site_url'].'/inscription-newsletter/' ;?> "><?php _e("Inscription newsletter",AFMM_TERMS)?></a>
        </div>
        <nav class="navbar navbar-expand-lg">
            <?php include(locate_template('template-parts/header/mobile_container.php')); ?>

            <div class="nav-menu collapse navbar-collapse nav-mobile-collapse" id="navbarMobileContent">
                <?php include(locate_template('template-parts/header/menu.php')); ?>
            </div>
            <?php
            if(PROJECT_NAME == 'afmm')include(locate_template('template-parts/forms/search_form.php'));
            ?>

        </nav>

    </header>

