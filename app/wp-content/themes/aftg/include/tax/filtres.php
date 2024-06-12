<?php
class Filtres
{
	const TAX = "filtre";

	public static function register()
	{
		self::custom_taxonomy_filtre();
	}

	private static function  custom_taxonomy_filtre()
	{
		$labels = array(
			'name'                       => 'Filtres',
			'singular_name'              => 'Filtre',
			'menu_name'                  => 'Filtres',
			'all_items'                  => 'Tous les Filtres',
			'parent_item'                => 'Filtre Parent ',
			'parent_item_colon'          => 'Filtre Parent:',
			'new_item_name'              => 'Nom Nouvelle Filtre',
			'add_new_item'               => 'Ajouter Nouvelle Filtre',
			'edit_item'                  => 'Editer Filtre',
			'update_item'                => 'Modifier Filtre',
			'search_items'               => 'Rechercher Filtre',
			'add_or_remove_items'        => 'Ajouter ou supprimer Filtre',
		);

		$args = array(
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'hierarchical'       => true,
			'labels'                     => $labels,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'				 => true,
		);
		register_taxonomy(self::TAX, 'adresse', $args);
	}
}
Filtres::register();
