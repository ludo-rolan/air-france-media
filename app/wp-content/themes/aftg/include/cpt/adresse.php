<?php



class Adresse
{

    const custom_post = "adresse";
    
        static function register()
    {

        add_action('init', [self::class, 'adresse_init']);
        add_action('restrict_manage_posts',[self::class,'destination_type_filter']);
        add_action('restrict_manage_posts',[self::class,'destination_type_filter_filters']);
    }
    static   function adresse_init()
    {
        $labels = array(
            'name'               => _x('Adresse', 'post type general name', AFMM_TERMS),
            'singular_name'      => _x('Adresse', 'post type singular name', AFMM_TERMS),
            'menu_name'          => _x('Adresse', 'admin menu', AFMM_TERMS),
            'name_admin_bar'     => _x('Adresse', 'add new on admin bar', AFMM_TERMS),
            'add_new'            => _x('Ajouter', 'Adresse', AFMM_TERMS),
            'add_new_item'       => __('Ajouter une nouvelle Adresse', AFMM_TERMS),
            'new_item'           => __('Nouveau Adresse', AFMM_TERMS),
            'edit_item'          => __('Editer Adresse', AFMM_TERMS),
            'view_item'          => __('Voir Adresse', AFMM_TERMS),
            'all_items'          => __('Tous les Adresse', AFMM_TERMS),
            'search_items'       => __('Rechercher Adresse', AFMM_TERMS),
            'parent_item_colon'  => __('Adresse Mère:', AFMM_TERMS),
            'not_found'          => __('Aucune Adresse trouvé.', AFMM_TERMS),
            'not_found_in_trash' => __('Aucune Adresse trouvé dans la corbeille.', AFMM_TERMS)
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
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail',"excerpt" ),
            'taxonomies'         => array( 'category', 'post_tag' ),
            'menu_icon'          => 'dashicons-location-alt',
            'show_in_rest'       => true,
        );

        register_post_type(self::custom_post, $args);
	    register_taxonomy_for_object_type("destinations", self::custom_post);
    }
    static function destination_type_filter() {
	
        global $typenow;
                
        $taxonomy	= 'destinations';		
        
        if( self::custom_post == $typenow ) {
            
            $selected		= isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
            $info_taxonomy	= get_taxonomy( $taxonomy );
            
            wp_dropdown_categories( array(
                'show_option_all'	=> esc_html__( "Show All {$info_taxonomy->label}" ),
                'taxonomy'			=> $taxonomy,
                'name'				=> $taxonomy,
                'orderby'			=> 'name',
                'selected'			=> $selected,
                'value_field'		=> 'slug',
                'show_count'		=> false,
                'hide_empty'		=> true,
            ) );
            
        }
    }

    static function destination_type_filter_filters(){
        global $typenow;
        $taxonomy = 'filtre';
        if($typenow == self::custom_post){

            $selected		= isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
            $info_taxonomy	= get_taxonomy( $taxonomy );
            
            wp_dropdown_categories( array(
                'show_option_all'	=> esc_html__( "Show All {$info_taxonomy->label}" ),
                'taxonomy'			=> $taxonomy,
                'name'				=> $taxonomy,
                'orderby'			=> 'name',
                'selected'			=> $selected,
                'value_field'		=> 'slug',
                'show_count'		=> false,
                'hide_empty'		=> true,
            ) );
        }
    } 

}

Adresse::register();
