<?php
global $post;
$category_detail = get_the_category($post->ID);

$args = [
    "post_type" => 'post',
    "posts_per_page" => 3,
    'cat'=> isset($category_detail) ? $category_detail[0]->term_id : '',
    'post__not_in' => array($post->ID),
];
$query = new WP_Query($args);

?>
<h2 class="a_lire_title text-uppercase"><?php _e('À lire aussi', AFMM_TERMS) ?></h2>
<div class="row">
    <?php
    $i = 0;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $link = get_permalink();
            $category_detail = get_the_category(get_the_ID());
    ?>

            <div class="col-md-4 a_lire">
                <a class="post_link" href="<?php echo $link ?>">
                    <?php
                    echo get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'a_lire_image, post_image']);
                    ?>

                </a>
                <div class="a_lire_info">

                    <span class="a_lire_cat">
                        <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
                            <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
                        </a>
                    </span>
                    <h3 class="post_title"><a href="javascript:void(0);" data-href="<?php echo $link ?>"><?php echo   get_the_title() ?> </a></h3>
                </div>
            </div>

    <?php


            $i++;
        }
    }

    ?>
</div>