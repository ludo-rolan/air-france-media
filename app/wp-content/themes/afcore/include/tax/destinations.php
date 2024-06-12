<?php
class Destinations
{
    const TAX = "destinations";

	public static function register()
    {
		self::custom_taxonomy_destination();
	}

    private static function  custom_taxonomy_destination()
    {
        $labels = array(
            'name'                       => 'Destinations',
            'singular_name'              => 'Destination',
            'menu_name'                  => 'Destinations',
            'all_items'                  => 'Tous les Destinations',
            'parent_item'                => 'Destination Parent ',
            'parent_item_colon'          => 'Destination Parent:',
            'new_item_name'              => 'Nom Nouvelle Destination',
            'add_new_item'               => 'Ajouter Nouvelle Destination',
            'edit_item'                  => 'Editer Destination',
            'update_item'                => 'Modifier Destination',
            'search_items'               => 'Rechercher Destination',
            'add_or_remove_items'        => 'Ajouter ou supprimer Destination',
        );
		//for debug purposes
	    $show_destination = WP_DEBUG || isset($_GET['show_destination']);
        $args = array(
            'publicly_queryable' => true,
            'query_var' => true,
            'rewrite' => true,
            'hierarchical'       => true,
            'labels'                     => $labels,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => $show_destination,
            'show_tagcloud'              => true,
            'show_in_rest'              => true,
        );
        register_taxonomy(self::TAX, 'destination', $args);
        register_taxonomy_for_object_type(self::TAX, 'post');
    }
}
Destinations::register();