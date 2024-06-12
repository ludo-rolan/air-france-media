<?php
class Envies
{
    const TAX = "envies";

	public static function register()
    {
		self::custom_taxonomy_envie();
	}

    private static function  custom_taxonomy_envie()
    {
        $labels = array(
            'name'                       => 'Envies',
            'singular_name'              => 'Envie',
            'menu_name'                  => 'Envies',
            'all_items'                  => 'Tous les Envies',
            'parent_item'                => 'Envie Parent ',
            'parent_item_colon'          => 'Envie Parent:',
            'new_item_name'              => 'Nom Nouvelle Envie',
            'add_new_item'               => 'Ajouter Nouvelle Envie',
            'edit_item'                  => 'Editer Envie',
            'update_item'                => 'Modifier Envie',
            'search_items'               => 'Rechercher Envie',
            'add_or_remove_items'        => 'Ajouter ou supprimer Envie',
        );
	
        $args = array(
            'publicly_queryable' => true,
            'query_var' => true,
            'rewrite' => true,
            'hierarchical'       => false,
            'labels'                     => $labels,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy(self::TAX, 'destination', $args);
       
    }
}
Envies::register();