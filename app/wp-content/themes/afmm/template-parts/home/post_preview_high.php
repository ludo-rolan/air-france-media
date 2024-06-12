<a class="hp_alune_link" href="<?php echo $link ?>">

<?php echo get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'hp_alune_image ']);
?>
</a>
<div class="hp_alune">
    <span class="hp_alune_cat">
        <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
                <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
        </a>
    </span>
    <h3 class="hp_alune_title"><a href="javascript:void(0);" data-href="<?php echo $link ?>"><?php echo   get_the_title() ?> </a></h3>
</div>