<?php echo do_shortcode("[category_name label='" . $name_category  . "' ]") ?>

<div class="container">

    <div class="row preview">
        <div class="col-md-12 ">
            <?php
            global $option_hp, $site_config, $posts_exclude;
            $hp_show_category_posts_args = ['ids' => $option_hp[$key_option], 'max_posts_number' => 1];
            $args = apply_filters('hp_show_category_posts', $hp_show_category_posts_args, $name_category);
            $cache_key = $site_config['afmm_cache']['hp_posts_preview_wide']['key'].$name_category;
            $cache_time = $site_config['afmm_cache']['hp_posts_preview_wide']['time'];
            $query = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function() use ($args){
                return new WP_Query( $args );
            });
            if (isset($query->posts[0]) && !empty($query->posts[0]->ID)) {
	            $posts_exclude[] = $query->posts[0]->ID;
            }
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $category_detail = get_the_category(get_the_ID());
                    $link = get_permalink();
            ?>
                    <a class="preview_link" href="<?php echo $link ?>">

                        <?php
                        echo get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'preview_image']);
                        ?>
                        </a>
                        <div class="preview_content">
                            <span class="preview_cat">
                                <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
                                    <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
                                </a>
                            </span>
                            <h3 class="preview_title"><a href="javascript:void(0)" data-href="<?php echo $link ?>"><?php echo   get_the_title() ?> </a></h3>
                        </div>
                <?php

                }
            } ?>


        </div>
    </div>



</div>
<?php echo do_shortcode('[post_preview_elem max_posts_number='.$max_posts_number.' bloc='. $name_category .' col=4 ids=' . $option_hp[$key_option_posts] . ']') ?>