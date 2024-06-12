<!DOCTYPE html>

<html <?php language_attributes(); ?>>
<?php
$is_category_or_home=is_home() || is_category() ;
global $site_config;
global $read_time_title;
global $sitepress;
$ismobile = $site_config['ismobile'];
$is_load_read_time = (!$ismobile &&  is_singular(['post','travel-guide','adresse']));
$read_time_header_class =  $is_load_read_time ? 'read-time-header fixed-top' : '';
$current_language = $site_config['langue_selectionner'] !== 'fr' ? $site_config['langue_selectionner'] . '/' : '';
$newsletter_link = $site_config['origin_url'] . $current_language .'inscription-newsletter/';
if(!$is_category_or_home || PROJECT_NAME == 'aftg'){

    include(locate_template('template-parts/header/head.php'));

?>


<body <?php body_class(); ?>>
<style>.skin_size_1000  header {margin: 0 auto; width: 1000px;background-color: white;margin-top: -33px;}</style>
<style>.skin_size_1000 .post_link {z-index: 100;}</style>
<style>.skin_size_1000 .native-contianer {margin-top:0!important;margin-bottom: 0!important;}</style>
<style>.skin_size_1000 .navbar .navbar-expand-lg {background-color: white;}</style>
<style>.skin_size_1000 .crumbs {background-color: white;max-width: 1000px;margin: 0px auto -8px;padding-top:33px}</style>
<style>.skin_size_1000 .mt-5 {padding-top: 3rem!important;margin-top: 0px !important;}</style>
<style>.skin_size_1000 a.sidebar_plusLus_link {z-index: 1;}</style>
<style>.pub_dfp .habillage .filled-with-pub {width: 1000px;margin: 0 auto; background: white;}</style>
<style>.skin_size_1000 #Calque_1 {display: none;}</style>
<style>.skin_size_1000 .block-partager-social{max-width: 1000px;background: white;margin: 0 auto;padding-bottom: 25px;}   </style>
<style>.skin_size_1000 #dfp_habillage{height:0px;}</style>
<?php wp_body_open(); 
}
?>

<header id="nothomeheader" style="background-color:white;" <?php echo (PROJECT_NAME == 'aftg') ? 'id="aftg-header"': ''; ?><?php
    if(( !is_front_page() || !is_home() )) {
        echo 'class="'. $read_time_header_class.'"';
    }?>
    >
    <nav class=" navbar navbar-expand-lg">
    <?php     include(locate_template('template-parts/header/mobile_container.php')); ?>
    
        <?php
            $sticky ='';
            if ($is_category_or_home && !$ismobile) {
	            PROJECT_NAME == 'aftg'? $sticky="aftg-sticky" : $sticky="sticky";
        }?>
        <div id="navbarMobileContent" class=" <?php echo $sticky?> nav-menu collapse navbar-collapse">
            <div>
                <a href="<?php echo $site_config['langue_selectionner'] !== 'fr' ? $site_config['origin_url'].$site_config['langue_selectionner'] : $site_config['origin_url'] ?>">
                    <img loading="lazy" class="nav-desktop-logo" src="<?php echo $site_config['logo_header'] ?>">
                </a>
            </div>
            <?php if ($is_load_read_time)  {
                global $read_time;?>
                <span class="read-time-header-block">
                    <span class="read-time-header-block-time">
                        <span class="fa fa-clock-o"></span>
                        <span> <?php echo $read_time;?></span>
                        <span class="read-time-header-block-title"><?php echo $read_time_title; ?></span>
                    </span>
                </span>
            <?php } ?>
            <?php     include(locate_template('template-parts/header/menu.php')); ?>
            <?php
            if(PROJECT_NAME == 'aftg'){
                ?>
            <div class="nav-desktop-carnet">
                <a href="<?php echo $site_config['site_url'].'/mon-carnet-de-voyage/' ;?> " class="hidden"></a>
            </div>
            <?php
            }
            ?>
            <div class="nav-desktop-language">
            <?php     include(locate_template('template-parts/header/lang-selection.php')); ?>
            </div>
            <div class="nav-desktop-third-block">
                <a class="btn af-btn <?php echo (PROJECT_NAME == 'aftg') ? 'newsletter-btn' : '' ;?>" href="<?php echo $newsletter_link;?> "><?php _e("Inscription newsletter",AFMM_TERMS) ?></a>
            </div>
           
        </div>
    </nav>
    <?php 
    do_action('end_header',$ismobile);
    ?>
    
</header>
