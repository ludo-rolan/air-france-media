<?php 
isset($is_side_bar) &&  $is_side_bar? $post_class = "sidebar_plusLus" : $post_class = "post";
$white= isset($white)?$white :'';
$category_detail = get_the_category(get_the_ID());
$link = get_permalink();
$partner="";
if ( has_term('', 'partenaires') ) {
    $partner="post_partner";
} 
?>

<a class="<?php echo $post_class; ?>_link" href="javascript:void(0);" data-href="<?php echo $link ?>">
    <?php
        $image_dimensions = apply_filters('select_cropped_img', ['custom_size' => 'thumb-w-360', 'original_size' => 'large']);
        echo get_the_post_thumbnail(get_the_ID(), $image_dimensions, ['class' => $post_class."_image".$white ]);
    ?>
</a>
<div class="<?php echo $post_class; ?>_info">

    <span class="<?php echo $post_class; ?>_cat<?php echo $white.' '.$partner ?> ">
    <?php if(!$is_side_bar){ ?>
        <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
        <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
        </a>
    <?php }else{
        echo $adress_tag;
    } ?>
    </span>
    <h3 class="<?php echo $post_class; ?>_title<?php echo $white; ?>"><a href="<?php echo $link ?>"><?php echo   get_the_title() ?> </a></h3>
</div>