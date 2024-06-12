<?php
// args à définir
$args = [];
$query = new WP_Query($args);

?>
<div class="row ">
    <?php
    $i = 0;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $link = get_permalink();
            $category_detail = get_the_category(get_the_ID());
    ?>
            <div class="col-md-6 post">
                <a class="post_link" href="<?php echo $link ?>">
                    <?php
                    echo get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'post_image_medium']);
                    ?>

                </a>
                <div class="post_info">

                    <span class="post_cat_medium">
                        <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
                            <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
                        </a>
                    </span>
                    <h3 class="post_title_medium"><a href="javascript:void(0);" data-href="<?php echo $link ?>"><?php echo   get_the_title() ?> </a></h3>
                </div>
            </div>
    <?php


            $i++;
        }
    }

    ?>
</div>
</div>