<?php

    class Omep
    {
        const GROUP = 'omep';
        const OPTION = self::GROUP . '_option';
        public static  $option_value = "";
        public static  function register()
        {
            if (is_admin()) {
                add_action('admin_menu', [self::class, 'add_menu']);
                add_action('admin_init', [self::class, 'registerSettings']);
            }
            self::$option_value = get_option(self::OPTION);

        }
        public static function registerSettings()
        {
            global $omeps;
            register_setting(self::GROUP, self::OPTION);
            add_settings_section('omepPage', 'Page Omep', function () {
                echo "Vous pouvez configurer les omeps du site";
            }, self::GROUP);
            if ($omeps && count($omeps) ){
                foreach ($omeps as $key => $omep) {
                    add_settings_field(
                        $key . 'option',
                        $omep['desc'],
                        function () use ($key, $omep) {
                            self::select_input($key, $omep);
                        },
                        self::GROUP,
                        'omepPage'
                    );
                }
            }
        }
        public static  function add_menu()
        {
            add_options_page(
                "Gestion omep",
                "Gestion omep ",
                'manage_options',
                self::GROUP,
                [self::class, 'render']
            );
        }
        static function select_input($key, $omep)
        {
            $value = isset(self::$option_value[$key]) ? self::$option_value[$key] : '';
?>
            <select name="<?php echo self::OPTION . '[' . $key . ']' ?>" id="">
                <option <?php selected($value, 0); ?> value="0">Désactivé</option>
                <option <?php selected($value, 1); ?>value="1">Activé</option>
            </select>
            <a href="https://trackers.pilotsystems.net/rmf-af/<?php echo $omep['numero'] ?>" target="_blank"> Lien ticket </a>
        <?php
        }
        public static  function render()

        {
        ?>
            <div class="wrap">
                <h1> Gestion des omeps </h1>
                <form method="post" action="options.php">
                    <div class="wrapper">
                        <?php submit_button(); ?>

                        <?php
                        settings_fields(self::GROUP);
                        do_settings_sections(self::GROUP);
                        ?>

                        <?php


                        submit_button();
                        ?>
                </form>

            </div>
<?php
        }
    }

Omep::register();

