<?php
class EmailPageMenu
{
    const GROUP = 'emails_options';
    public static function register()
    {
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }

    public static function addMenu()
    {
        add_options_page("Gestion des Emails pour l'envoi AF", "Emails", "manage_options", self::GROUP, [self::class, 'render']);
    }

    public static function render()
    {
?>
        <h1>Gestion des Emails:</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields(self::GROUP);
            do_settings_sections(self::GROUP);
            submit_button();
            ?>
        </form>
        <?php
    }

    public static function makeInput($input, $name, $title, $enabled = true)
    {
        register_setting(self::GROUP, $name);
        add_settings_field('emails_options_' . $name, $title, function ($args) {
            if ($args['input'] == 'textarea') {
        ?>
                <textarea <?php echo $args['enabled'] ? '' : 'disabled' ?> name="<?php echo ($args['name']); ?>" cols="30" rows="10" style="height: 100px;width:50%"><?php echo esc_html(get_option($args['name'])) ?></textarea>
            <?php
            } elseif ($args['input'] == 'checkbox') {
            ?>
                <input <?php echo $args['enabled'] ? '' : 'disabled' ?> type="checkbox" name="<?php echo ($args['name']); ?>" value="1" <?php checked(1, get_option($args['name']), true); ?>>
<?php
            }
        }, self::GROUP, 'emails_options_section', ['name' => $name, 'input' => $input, 'enabled' => $enabled]);
    }

    public static function registerSettings()
    {
        add_settings_section('emails_options_section', 'Paramètre', function () {
            echo "Veuillez séparer les emails par (;)";
        }, self::GROUP);

        // emails for hot posts
        self::makeInput('textarea', 'emails', 'posts chauds');


        // disable sending hot posts 
        self::makeInput('checkbox', 'enablehotpost', "activer l'envoi des posts chauds");

        //emails for streams
        self::makeInput('textarea', 'flux', 'flux');

        // disable sending streams
        self::makeInput('checkbox', 'enableflux', "activer l'envoi des flux");

        // logs for sending streams
        self::makeInput('textarea', 'fluxlogs', 'logs pour les flux', false);
    }
}

EmailPageMenu::register();
