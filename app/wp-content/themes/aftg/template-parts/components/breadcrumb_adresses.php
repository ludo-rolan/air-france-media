<?php
$delimiter = '<span> &rsaquo; </span>';
?>
<div class='adresse_breadcrumb'>
    <div>
        <a href="/">
            <?php _e("accueil",AFMM_TERMS) ?>
        </a>
        <?php echo $delimiter ?>
        <a href="">
            <?php  _e("travel guide",AFMM_TERMS)?>
        </a>
        <?php echo $delimiter ?>
        <a href="">
            <?php  _e("destinations",AFMM_TERMS) ?>
        </a>
        <?php if($term_parent) {
            echo $delimiter;
        ?>
        <a href="<?php echo get_term_link($term_parent); ?>">
            <?php echo $term_parent->name; ?>
        </a>
        <?php 
        }
        echo $delimiter ?>
        <!-- $href_breadcrumb retourne wp_error if term_id n'existe pas -->
        <?php 
        $posts = get_posts(array(
            'post_type' => 'destination',
            'numberposts' => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'destinations',
                    'field' => 'slug',
                    'terms' => $term->slug,
                    'include_children' => false
                )
            )
        ));
        if(count($posts) && isset ($posts[0])){
        ?>
        <a href="<?php echo get_permalink($posts[0]->ID) ?>">
            <?php echo $posts[0]->post_title; ?>
        </a>
        <?php } ?>
    </div>
</div>