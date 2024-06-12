<?php
class Partenaires
{
    const TAX = "partenaires";
    const url = self ::TAX .'-url';

    const fields = array(
        [self::url, 'Url'],
    );

    public static  function register()
    {
        add_action('init', [self::class, 'custom_taxonomy_Partenaire']);
        add_action( self::TAX . '_add_form_fields', [self::class, 'add_term_partenaires'], 10, 2);
        add_action( self::TAX . '_edit_form_fields', [self::class, 'edit_term_partenaires'], 10, 2 );  
        add_action( 'created_'. self::TAX, [self::class, 'save_partenaires_fields']);   
        add_action( 'edited_'. self::TAX, [self::class, 'update_partenaires_fields']);  
    }

    static  function  custom_taxonomy_Partenaire()
    {
        $labels = array(
            'name'                       => 'Partenaires',
            'singular_name'              => 'Partenaire',
            'menu_name'                  => 'Partenaires',
            'all_items'                  => 'Tous les Partenaires',
            'parent_item'                => 'Partenaire Parent ',
            'parent_item_colon'          => 'Partenaire Parent:',
            'new_item_name'              => 'Nom Nouvelle Partenaire',
            'add_new_item'               => 'Ajouter Nouvelle Partenaire',
            'edit_item'                  => 'Editer Partenaire',
            'update_item'                => 'Modifier Partenaire',
            'search_items'               => 'Rechercher Partenaire',
            'add_or_remove_items'        => 'Ajouter ou supprimer Partenaire',
        );
        $args = array(
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "show_admin_column" => true,
            "show_in_rest" => true,

        );
        register_taxonomy(self::TAX, apply_filters('partenaires_post_type', ''), $args);

    }

    static function add_term_partenaires($taxonomy)
    {
        foreach (self::fields as $meta_key_value) {
            self::text_input($meta_key_value[1], $meta_key_value[0]);
        }
    }

    static function save_partenaires_fields($term_id)
    {
        foreach (self::fields as $meta_key_value) {
            if (isset($_POST[$meta_key_value[0]]) && $_POST[$meta_key_value[0]] !== '') {
                $meta_value = $_POST[$meta_key_value[0]];
                add_term_meta($term_id, $meta_key_value[0], $meta_value);
            }
        }
    }

    static function edit_term_partenaires($term, $taxonomy)
    {
        foreach (self::fields as $meta_key_value) {
            self::update_text_input($term, $meta_key_value[1], $meta_key_value[0]);
        }
    }

    static function update_partenaires_fields($term_id)
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
Partenaires::register();
