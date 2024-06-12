<?php
class Mois
{
    const TAX = "mois";

	public static function register()
    {
		self::custom_taxonomy_mois();
	}

    private static function  custom_taxonomy_mois()
    {
        $labels = array(
            'name'                       => 'Mois',
            'singular_name'              => 'Mois',
            'menu_name'                  => 'Mois',
            'all_items'                  => 'Tous les Mois',
            'parent_item'                => 'Mois Parent ',
            'parent_item_colon'          => 'Mois Parent:',
            'new_item_name'              => 'Nom Nouvelle Mois',
            'add_new_item'               => 'Ajouter Nouvelle Mois',
            'edit_item'                  => 'Editer Mois',
            'update_item'                => 'Modifier Mois',
            'search_items'               => 'Rechercher Mois',
            'add_or_remove_items'        => 'Ajouter ou supprimer Mois',
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
Mois::register();