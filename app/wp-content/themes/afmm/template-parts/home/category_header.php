<?php 
global $site_config;
$white='';
$is_white ? $white = "-envol" : $white = '';
$is_white ? $class = "  cat_container".$white : $class = '';
$is_white ? $class_row = " container row" : $class_row = 'row';
$cat = get_category_by_slug($category_slug);
$cat_id= $cat? $cat->term_id :0;
$link=get_category_link( $cat_id);
?>
<div class="<?php echo $class; ?> ">
<div class="row cat<?php echo $white; ?>">

    <div class="col-md-9 ">
        <h2 class="cat_name<?php echo $white; ?>">
         <?php echo _e($site_config['hp_cats'][$category_slug], AFMM_TERMS) ?> </h2>
    </div>
    <?php if(!$category_slug== 'a-bord'){ ?>
    <div class="col-md-3">
        <a href="<?php echo $link; ?>" class="cat_link<?php echo $white; ?>"><?php _e("tout voir", AFMM_TERMS) ?></a>
    </div>
    <?php } ?>
</div>
</div>