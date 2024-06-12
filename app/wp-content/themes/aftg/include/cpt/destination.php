<?php



class Destination
{

    const custom_post = "destination";
    
        static function register()
    {

        add_action('init', [self::class, 'Destination_init']);
        add_filter( 'use_block_editor_for_post_type', [self::class,'disable_gutenberg'], 10, 2 );

    }
    static   function Destination_init()
    {
        $labels = array(
            'name'               => _x('Destination', 'post type general name', AFMM_TERMS),
            'singular_name'      => _x('Destination', 'post type singular name', AFMM_TERMS),
            'menu_name'          => _x('Destination', 'admin menu', AFMM_TERMS),
            'name_admin_bar'     => _x('Destination', 'add new on admin bar', AFMM_TERMS),
            'add_new'            => _x('Ajouter', 'Destination', AFMM_TERMS),
            'add_new_item'       => __('Ajouter une nouvelle Destination', AFMM_TERMS),
            'new_item'           => __('Nouveau Destination', AFMM_TERMS),
            'edit_item'          => __('Editer Destination', AFMM_TERMS),
            'view_item'          => __('Voir Destination', AFMM_TERMS),
            'all_items'          => __('Tous les Destination', AFMM_TERMS),
            'search_items'       => __('Rechercher Destination', AFMM_TERMS),
            'parent_item_colon'  => __('Destination Mère:', AFMM_TERMS),
            'not_found'          => __('Aucune Destination trouvé.', AFMM_TERMS),
            'not_found_in_trash' => __('Aucune Destination trouvé dans la corbeille.', AFMM_TERMS)
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
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
            'taxonomies'         => array( 'category', 'post_tag' ),
            'menu_icon'          => 'dashicons-location-alt',
            'show_in_rest'       => true,

        );

        register_post_type(self::custom_post, $args);
    }


    static function disable_gutenberg( $is_enabled, $post_type ) {
            if ( self::custom_post == $post_type ) {  
                return false;
            }
        
            return $is_enabled;
        }
}

Destination::register();
