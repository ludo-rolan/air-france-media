<?php
class Aux_alentours
{
    const TAX = "aux_alentours";
    const kilometrage = self::TAX . '-kilometrage';
    const city = self::TAX . '-city';
    const title_preview = self ::TAX .'-title_preview';
    // ajouter des inputs personnalisés (pour les adaptés aux JSs)
    const latitude = 'localisation_latitude';
    const longitude = 'localisation_longitude';
    const map = 'localisation_map';

    const fields = array(
        [self::kilometrage, 'Kilométrage'], 
        [self::city, 'Ville'], 
        [self::title_preview ,'Title preview'],
        [self::latitude, 'Latitude'],
        [self::longitude, 'Longitude'],
        [self::map,'map']
    );


	public static function register()
    {
		add_action( 'init', [self::class, 'custom_taxonomy_aux_alentours']);
        add_action( self::TAX . '_add_form_fields', [self::class, 'add_term_aux_alentours'], 10, 2);
        add_action( self::TAX . '_edit_form_fields', [self::class, 'edit_term_aux_alentours'], 10, 2 );  
        add_action( 'created_'. self::TAX, [self::class, 'save_aux_alentours_fields']);   
        add_action( 'edited_'. self::TAX, [self::class, 'update_aux_alentours_fields']);  
	}

    static function  custom_taxonomy_aux_alentours()
    {
        $labels = array(
            'name'                       => 'Aux Alentours',
            'singular_name'              => 'Aux Alentours',
            'menu_name'                  => 'Aux Alentours',
            'all_items'                  => 'Tous les Aux Alentours',
            'parent_item'                => 'Aux Alentours Parent ',
            'parent_item_colon'          => 'Aux Alentours Parent:',
            'new_item_name'              => 'Nom Nouvelle Aux Alentours',
            'add_new_item'               => 'Ajouter Nouvelle Aux Alentours',
            'edit_item'                  => 'Editer Aux Alentours',
            'update_item'                => 'Modifier Aux Alentours',
            'search_items'               => 'Rechercher Aux Alentours',
            'add_or_remove_items'        => 'Ajouter ou supprimer Aux Alentours',
        );
	
        $args = array(
            'publicly_queryable'         => true,
            'query_var'                  => true,
            'rewrite'                    => true,
            'hierarchical'               => false,
            'labels'                     => $labels,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy(self::TAX, array('adresse', 'destination'), $args);
       
    }

    static function add_term_aux_alentours($taxonomy)
    {
        foreach (self::fields as $meta_key_value) {
            self::text_input($meta_key_value[1], $meta_key_value[0]);
        }
    }

    static function save_aux_alentours_fields($term_id)
    {
        foreach (self::fields as $meta_key_value) {
            if (isset($_POST[$meta_key_value[0]]) && $_POST[$meta_key_value[0]] !== '') {
                $meta_value = $_POST[$meta_key_value[0]];
                add_term_meta($term_id, $meta_key_value[0], $meta_value);
            }
        }
    }

    static function edit_term_aux_alentours($term, $taxonomy)
    {
        foreach (self::fields as $meta_key_value) {
            self::update_text_input($term, $meta_key_value[1], $meta_key_value[0]);
        }
        echo "<div id='localisation'></div>";
    }

    static function update_aux_alentours_fields($term_id)
    {
        foreach (self::fields as $meta_key_value) {
            if (isset($_POST[$meta_key_value[0]]) &&  $_POST[$meta_key_value[0]] !== '') {
                $meta_value = $_POST[$meta_key_value[0]];
                update_term_meta($term_id, $meta_key_value[0], $meta_value);
            } else {
                update_term_meta($term_id, $meta_key_value[0], '');
            }
        }
    }

    static function text_input($name, $type)
    {
    ?>
        <div class="form-field term-group">
            <label for="<?php echo $type ?>"><?php _e($name, AFMM_TERMS); ?></label>
            <input type="text" id="<?php echo $type ?>" name="<?php echo $type ?>" value="">
        </div>
    <?php
    }

    static function update_text_input($term, $name, $type)
    {
    ?>
        <tr class="form-field term-group-wrap">
            <th>
                <label for="<?php echo $type ?>"><?php _e($name, AFMM_TERMS); ?></label>
                <?php $value = ($term != null) ? get_term_meta($term->term_id, $type, true) : ''; ?>
            </th>
            <td>
                <input type="text" id="<?php echo $type ?>" name="<?php echo $type ?>" value="<?php echo esc_html($value) ?>">
            </td>
        </tr>
    <?php
    }

}
Aux_alentours::register();