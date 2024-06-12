<?php
Maillage::get_afmm_menu("header");
if(PROJECT_NAME == 'afmm'){
?>
<i data-action="rechercher" data-toggle="modal" data-target="#MainSearch" id="loupe_search" class=" rechercher-btn fa fa-search">
</i>
<?php
}
?>
<div class="nav-mobile-newsletter">
    <a class="btn af-btn  <?php echo (PROJECT_NAME == 'aftg') ? 'newsletter-btn' : '' ;?> " href="<?php echo $newsletter_link;?> "><?php _e("Inscription newsletter",AFMM_TERMS) ?></a>
</div>
<?php
if (PROJECT_NAME == 'aftg') {
?>
<div class="nav-mobile-carnet">
    <a href="<?php echo $site_config['site_url'].'/mon-carnet-de-voyage/' ;?> " class="hidden"></a>
</div>
<?php
}
?>

<div class="nav-mobile-language">
<?php     include(locate_template('template-parts/header/lang-selection.php')); ?>
</div>
