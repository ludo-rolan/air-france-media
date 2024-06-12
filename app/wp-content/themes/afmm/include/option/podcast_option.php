<?php

    
class Podcast
{
    const GROUP = 'podcast_option';
    const PID = self::GROUP . '_data_pid';
    const GID = self::GROUP . '_data_gid';
    const PLACE_ID = self::GROUP . '_placement_id';

    const OPTIONS = array(self::PID,self::GID,self::PLACE_ID);

    public static  function register()
    {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }
    public static function registerSettings()
    {
        foreach (self::OPTIONS as $op) {
            register_setting(self::GROUP, $op);
        }
    
        add_settings_section('PodcastPage', 'Edisound', function () {
            
        }, self::GROUP);
       
        add_settings_field(self::PID . 'option', 'data ID ', function ()   {self::text_input(self::PID);
        }, self::GROUP, 'PodcastPage');
        add_settings_field(self::GID . 'option', 'Data GID', function  ()  {self::text_input(self::GID);
        }, self::GROUP, 'PodcastPage');
        add_settings_field(self::PLACE_ID . 'option', 'Placement ID ', function () {self::text_input(self::PLACE_ID);
        }, self::GROUP, 'PodcastPage');
    
    }
    public static  function add_menu()
    {
        add_options_page(
            "Podcasts",
            "Podcasts",
            'manage_options',
            self::GROUP,
            [self::class, 'render']
        );
    }
    public static  function render()
    {
?>
        <div class="wrap">
            <h1>Podcasts</h1>
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

    static function text_input($type)
    {
    ?>
        <div>
            <input type="text" id="<?php echo $type ?>" name="<?php echo $type ?>" value="<?php echo get_option($type) ?>">
        </div>
<?php
    }
    
}
Podcast::register();

   
    