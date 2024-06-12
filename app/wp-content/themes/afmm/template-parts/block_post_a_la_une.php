<?php echo do_shortcode("[dfp id='mobile_1']") ?>
<section class="container">
    <div class="row  text-center ">
        <div class="col-md-8 hp_alune">
            <?php
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    if ($query->current_post === 0) {
                        $category_detail = get_the_category(get_the_ID(), array('fields' => 'ids'));
                        $link = get_permalink();
            ?>
                        <a class="hp_alune_link" href="<?php echo $link ?>">
                            <?php
                            echo get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'hp_alune_image ']);
                            ?>
                        </a>
                        <div class="hp_alune">
                            <span class="hp_alune_cat">
                                <a href="<?php echo Af_Category_Helper::get_post_categorie_link($category_detail) ?>">
                                    <?php echo (Af_Category_Helper::get_post_categorie_name($category_detail)); ?>
                                </a>
                            </span>
                            <h3 class="hp_alune_title">
                                <a href="<?php echo $link ?>"><?php echo get_the_title() ?></a>
                            </h3>
                            <a href="javascript:void(0)" data-href="<?php echo $link ?>" class="hp_alune_btn"><?php _e('lire la suite', AFMM_TERMS); ?></a>
                        </div>
            <?php
                    }
                }
            } ?>
        </div>
        <div class="col-md-4  d-flex justify-content-center">
            <div id="RougeDiorAD" class="">
                <?php echo do_shortcode("[dfp id='mpu_haut']") ?>
            </div>
        </div>
    </div>
</section>