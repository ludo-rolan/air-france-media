<div class="row inspirations-row">
    <?php
    global $site_config, $posts_exclude;
    $cache_key = $site_config['afmm_cache']['hp_inspiration']['key'];
    $cache_time = $site_config['afmm_cache']['hp_inspiration']['time'];
    $hp_show_category_posts_args = ['ids' => $ids, 'max_posts_number' => 4];
    $args = apply_filters('hp_show_category_posts', $hp_show_category_posts_args, 'inspirations');
    $query  = get_data_from_cache($cache_key,'hp_locking_ids',$cache_time,function() use ($args){
        return new WP_Query( $args );
    });
    if (isset($query->posts[0]) && !empty($query->posts[0]->ID)) {
	    $posts_exclude[] = $query->posts[0]->ID;
    }
    $i = 0;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            if ($i == 0 || $i == 2) {
    ?>
                <div class="inspirations-col">
                <?php
            }
                $query->the_post();
                $link = get_permalink();
                $category_detail = get_the_category(get_the_ID());
                ?>
                    <div class=" post inspirations-content">
                        <?php include(locate_template('template-parts/home/post_preview_elem.php')); ?>
                    </div>
                <?php
            
            if ($i == 1 || $i == 3) {
                ?>
                </div>
    <?php
            }
            $i++;
        }
        
    }
    ?>
    <div class="inspirations-col">
        <div class=" inspirations-content d-flex justify-content-center">
            <div class="  inspirations-form">
                <?php include(locate_template("template-parts/forms/embarquement_form.php")); ?>
            </div>
        </div>
    </div>
</div>
