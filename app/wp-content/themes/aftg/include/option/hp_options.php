<?php
class Hp_Option
{
    const GROUP = 'aftg_hp_options';
    const destinations_hp = self::GROUP . '_destinations_hp';
    const destinations_content = self::GROUP . '_destinations_content';

    const OPTIONS = array(
        [self::destinations_hp, "IDs des 7 destinations"],
        [self::destinations_content, "Texte destination"]
    );
    public static $global_option ;


    public static  function register()
    {

        Hp_Option::$global_option = get_option(self::GROUP);

        add_action('admin_footer', [self::class, 'ajax_fetch']);
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'registerSettings']);
        add_action('hp_option_search_table_header', [self::class, 'hp_option_search_table_header']);
    }

    static function ajax_fetch()
    {
        $screen = get_current_screen();
         if ( is_admin() && in_array( $screen->id, array( 'settings_page_aftg_hp_options') ) ){
            wp_register_script("hp_options", AF_THEME_DIR_URI . '/assets/js/admin/hp_option.js', array('jquery'));
            wp_localize_script('hp_options', 'data', array('ajax_url' => admin_url('admin-ajax.php')));
            wp_enqueue_script('hp_options');
        }
    }

    public static function registerSettings()
    {

        add_settings_field('', 'Rechercher ', function () {

        do_action('hp_option_input_container', "destination");

        }, self::GROUP, 'home_page');
        register_setting(self::GROUP, self::GROUP);

        // liste des ids des destinations
        $ids_op = self::OPTIONS[0];
        add_settings_field($ids_op[0] . '_option', $ids_op[1], function () use ($ids_op) {
            self::text_input($ids_op[0], 'destination', true);
        }, self::GROUP, 'home_page');

        // boucle de 7 pour générer les inputs contenant le texte de chaque destination.
        $content_op = self::OPTIONS[1];
        for($i=1; $i<=7; $i++) {
            add_settings_field($content_op[0] . '_' . $i .'_option', $content_op[1] . ' ' . $i, function () use ($content_op, $i) {
                self::text_input($content_op[0] . '_' . $i, 'destination', false);
            }, self::GROUP, 'home_page');
        }

        add_settings_section('home_page', 'Home Page', function () {
            echo "Vous pouvez choisir les destinations de la Home Page ";
            echo " </br>La selection de la destination se fait par ID, coller l'id de la destination choisie dans la bonne case</br>Séparer les ids avec des virgules si besoin. par exemple 30,61,99";
        }, self::GROUP);
    }
    public static  function add_menu()
    {
        add_options_page(
            "Gestion HomePage",
            "Gestion HomePage ",
            'manage_options',
            self::GROUP,
            [self::class, 'render']
        );
    }

    static function hp_option_search_table_header($padding) {
        ?>
        <th style="<?php echo $padding ?>">Id destination</th>
        <th style="<?php echo $padding ?>">Titre</th>
        <th style="<?php echo $padding ?>">copier l'id</th>
        <th style="<?php echo $padding ?>">Voir destination</th>
        <?php 
    }
    
    public static  function render()
    {
        ?>
        <div class="wrap">
            <h1>Gestion de la home page</h1>

            <form method="post" action="options.php">

                <?php
                settings_fields(self::GROUP);
                do_settings_sections(self::GROUP);
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }
    static function text_input($type, $post_type ,$do_get_posts=false)
    {
        $name=self::GROUP."[" .$type. "]";
    ?>
        <div>
            <input type="text" id="<?php echo $name ?>" name="<?php echo $name ?>" value="<?php echo self::$global_option[$type] ?>">
            <?php
                // Le bloc qui affiche la liste des urls des destinations est dans le thème core.
                do_action('hp_option_post_links_list', self::$global_option[$type], $post_type ,$do_get_posts);
            ?>
        </div>
        <?php
    }

}

Hp_Option::register();
