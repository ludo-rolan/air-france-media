<?php



class Avion
{

    const custom_post = "avion";
    
        static function register()
    {

        add_action('init', [self::class, 'avion_init']);
    }
    static   function avion_init()
    {
        $labels = array(
            'name'               => _x('Avion', 'post type general name', AFMM_TERMS),
            'singular_name'      => _x('Avion', 'post type singular name', AFMM_TERMS),
            'menu_name'          => _x('Avion', 'admin menu', AFMM_TERMS),
            'name_admin_bar'     => _x('Avion', 'add new on admin bar', AFMM_TERMS),
            'add_new'            => _x('Ajouter', 'Avion', AFMM_TERMS),
            'add_new_item'       => __('Ajouter un nouveau Avion', AFMM_TERMS),
            'new_item'           => __('Nouveau Avion', AFMM_TERMS),
            'edit_item'          => __('Editer Avion', AFMM_TERMS),
            'view_item'          => __('Voir Avion', AFMM_TERMS),
            'all_items'          => __('Tous les Avion', AFMM_TERMS),
            'search_items'       => __('Rechercher Avion', AFMM_TERMS),
            'parent_item_colon'  => __('Parent Avion:', AFMM_TERMS),
            'not_found'          => __('Aucun Avion trouvé.', AFMM_TERMS),
            'not_found_in_trash' => __('Aucun Avion trouvé dans la corbeille.', AFMM_TERMS)
        );

        $args = array(
            'labels'             => $labels,
            'public'             =>true,
            'description'        => __('Description.', AFMM_TERMS),
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'menu_icon'          => 'dashicons-airplane',
        );

        register_post_type(self::custom_post, $args);
    }
}

Avion::register();
