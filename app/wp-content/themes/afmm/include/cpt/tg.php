<?php



class Tg
{

    const custom_post = "travel-guide";
    
        static function register()
    {

        add_action('init', [self::class, 'travel_guide_init']);
    }
    static   function travel_guide_init()
    {
        $labels = array(
            'name'               => _x('travel-guide', 'post type general name', AFMM_TERMS),
            'singular_name'      => _x('travel-guide', 'post type singular name', AFMM_TERMS),
            'menu_name'          => _x('travel-guide', 'admin menu', AFMM_TERMS),
            'name_admin_bar'     => _x('travel-guide', 'add new on admin bar', AFMM_TERMS),
            'add_new'            => _x('Ajouter', 'travel-guide', AFMM_TERMS),
            'add_new_item'       => __('Ajouter une nouvelle travel-guide', AFMM_TERMS),
            'new_item'           => __('Nouveau travel-guide', AFMM_TERMS),
            'edit_item'          => __('Editer travel-guide', AFMM_TERMS),
            'view_item'          => __('Voir travel-guide', AFMM_TERMS),
            'all_items'          => __('Tous les travel-guide', AFMM_TERMS),
            'search_items'       => __('Rechercher travel-guide', AFMM_TERMS),
            'parent_item_colon'  => __('travel-guide Mère:', AFMM_TERMS),
            'not_found'          => __('Aucune travel-guide trouvé.', AFMM_TERMS),
            'not_found_in_trash' => __('Aucune travel-guide trouvé dans la corbeille.', AFMM_TERMS)
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', AFMM_TERMS),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
            'taxonomies'         => array( 'category', 'post_tag' ),
        );

        register_post_type(self::custom_post, $args);
    }
}

Tg::register();
