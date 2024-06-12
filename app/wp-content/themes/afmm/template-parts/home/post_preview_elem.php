<?php 
global $site_config;
isset($is_side_bar) &&  $is_side_bar? $post_class = "sidebar_plusLus" : $post_class = "post";
$white= isset($white)?$white :'';
$category_detail = get_the_category(get_the_ID());
$link = get_permalink();
$partner="";
if ( has_term('', 'partenaires') ) {
    $partner="post_partner";
} 
?>

<a class="<?php echo $post_class; ?>_link" href="<?php echo $link ?>">
    <?php 
    echo get_the_post_thumbnail(get_the_ID(), 'large', ['class' => $post_class."_image".$white ]);
    ?>
</a>
<div class="<?php echo $post_class; ?>_info<?php echo $white; ?> ">

    <span class="<?php echo $post_class; ?>_cat<?php echo $white.' '.$partner ?> ">
    <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
    <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
    </a>
    <?php if($partner) : ?>
        <img class="post_handshake-icon"  src="<?php echo $site_config['inspirations_icon']['handshake']; ?>" alt="partenaire">
    <?php endif ?>
    </span>
    <h3 class="<?php echo $post_class; ?>_title<?php echo $white; ?>"><a href="javascript:void(0);" data-href="<?php echo $link ?>"><?php echo   get_the_title() ?> </a></h3>
</div>