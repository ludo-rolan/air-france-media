<?php
class Hp_Option

{   const GROUP="hp_options";
    const activate_habillage =  '_activate_habillage';
    const post_bandeau =  '_post_bandeau';
    const post_alune =  '_post_alune';
    const trois_post_alune =  '_trois_post_alune';
    const quatre_post_inspiration = '_quatre_post_inspiration';
    const post_evasion =  '_post_evasion';
    const trois_post_evasion = '_trois_post_evasion';
    const cinq_post_video = '_cinq_post_video';
    const cinq_post_podcast =  '_cinq_post_podcast';
    const post_styles =  '_post_styles';
    const trois_post_styles =  '_trois_post_styles';
    const trois_post_a_bord = '_trois_post_a_bord';
    const trois_post_tg =  '_trois_post_tg';
    const cinq_ville_tg = '_cinq_ville_tg';

    const OPTIONS = array(
        [self::post_bandeau, "Article Bandeau"],
        [self::post_alune, "Article à la une"],
        [self::trois_post_alune, "3 Articles à la une"],
        [self::quatre_post_inspiration, "4 Articles inspirations"],
        [self::post_evasion, "Article Evasion"],
        [self::trois_post_evasion, "3 Articles Evasion"],
        [self::cinq_post_video, "5 Articles Vidéo "],
        [self::cinq_post_podcast, "5 Articles Podcast"],
        [self::post_styles, "Article Styles"],
        [self::trois_post_styles, "3 Articles Syles"],
        [self::trois_post_a_bord, "3 Articles à bord"],
        [self::trois_post_tg, "3 Articles Travel guide"],
        [self::cinq_ville_tg, "5 Villes AFTG"],



    );
    public $global_option;
    public $lg;
    public $group="hp_options";
    public $page;

    public  function register($lg)
    {
        $this->group=$lg."_".$this->group;
        $this->global_option = get_option($this->group);
        $this->lg=$lg;
        if(is_admin()){
        add_action('admin_footer', [$this, 'ajax_fetch']);
       
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('hp_option_search_table_header', [self::class, 'hp_option_search_table_header']);
        }
        add_action('updated_option', [$this, 'purge_hp_option_on_save'], 10, 2);
    }

    function ajax_fetch()
    {

            $screen = get_current_screen();
            if (is_admin() && in_array($screen->id, array('settings_page_' . $this->lg . '_hp_options'))) {
                wp_register_script("hp_options", AF_THEME_DIR_URI . '/assets/js/admin/hp_option.js', array('jquery'));
                wp_localize_script('hp_options', 'data', array('ajax_url' => admin_url('admin-ajax.php')));
                wp_enqueue_script('hp_options');
            }
        
    }

    function registerSettings()
    {
        add_settings_field(self::activate_habillage, 'Activer habillage', function () {
            $type = self::activate_habillage;
            $name = $this->group . "[" . $type . "]";
            isset($this->global_option[$type]) ? $elem = $this->global_option[$type] : $elem = false;
?>
            <div>
                <input type="checkbox" id="<?php echo $name ?>" name="<?php echo $name ?>" value="1" <?php checked(1,  $elem); ?>>
            </div>
        <?php

        }, $this->group, 'home_page');
        register_setting($this->group, $this->group);

        add_settings_field('', 'Rechercher ', function () {

            do_action('hp_option_input_container', "post");
        }, $this->group, 'home_page');
        register_setting($this->group, $this->group);
        foreach (self::OPTIONS as $op) {
            $option_val=$this->group.$op[0];
            add_settings_field($option_val . '_option', $op[1], function () use ($option_val) {
                $post_type = $option_val == apply_filters('get_fields_deps_lang', 'hp_options_trois_post_tg') ? 'travel-guide' : 'post';
                self::text_input($option_val, $post_type, true);
            }, $this->group, 'home_page');
        }

        add_settings_section('home_page', 'Home Page', function () {
            echo " </br> Vous pouvez choisir les articles de la Home Page ";
            echo " </br>La selection de l'article se fait par ID, coller l'id de l'article choisi dans la bonne case</br>Séparer les ids avec des virgules si besoin. par exemple 30,61,99";
            echo '<h5>Date de modification :' . get_option("hp_locking_ids_purge") . '</h5>';
        }, $this->group);
    }
    public   function add_menu()
    {
            add_options_page(
                "Gestion HomePage",
                "Gestion HomePage " . $this->lg,
                'manage_options',
                $this->lg.'_hp_options',
                [$this, 'render']
            );
        
    }

    function purge_hp_option_on_save($option_name, $option_value)
    {
        if ($option_name === 'hp_options') {
            if (function_exists('w3tc_flush_all')) {
                w3tc_flush_all();
                global $current_user;
                wp_get_current_user();
                update_option("hp_locking_ids_purge", date('d-m-y h:i:s') . ' par : ' . $current_user->user_login);
            }
        }
    }


    static function hp_option_search_table_header($padding)
    {
     
        ?>
        <th style="<?php echo $padding ?>">Id article</th>
        <th style="<?php echo $padding ?>">Titre</th>
        <th style="<?php echo $padding ?>">copier l'id</th>
        <th style="<?php echo $padding ?>">Voir article</th>
    <?php
    }

    public  function render()
    {
    ?>
        <div class="wrap">
            <h1>Gestion de la home page <?php echo $this->group ?></h1>
            <form method="post" action="options.php">

                <?php
                settings_fields($this->group);
                do_settings_sections($this->group);
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }
    function text_input($type, $post_type, $do_get_posts = false)
    {
        $name = $this->group . "[" . $type . "]";
        isset($this->global_option[$type]) ? $elem = $this->global_option[$type] : $elem = '';

    ?>
        <div>
            <input type="text" id="<?php echo $name ?>" name="<?php echo $name ?>" value="<?php echo $elem ?>">
            <?php
            // Le bloc qui affiche la liste des urls des destinations est dans le thème core.
            do_action('hp_option_post_links_list', $elem, $post_type, $do_get_posts);
            ?>
        </div>
<?php
    }

    // this hook is used to display latest posts per category in case of having no id in the locking page
    
}

$hp_fr_locking = new Hp_Option();
$hp_fr_locking->register("fr");

$hp_en_locking = new Hp_Option();
$hp_en_locking->register("en");

add_filter('hp_show_category_posts', 'hp_show_category_posts_function', 10, 2);
 function hp_show_category_posts_function($hp_show_category_posts_args, $category_slug = null)
    {
        global  $posts_exclude;

        $ids              = $hp_show_category_posts_args['ids'] ?? null;
        $max_posts_number = $hp_show_category_posts_args['max_posts_number'] ?? null;
        $offset           = $hp_show_category_posts_args['offset'] ?? null;
        $cat              = get_category_by_slug($category_slug);
        $post_type        = $category_slug === 'tg' ? 'travel-guide' : 'post';
        // when there is no id in BO locking, prepare query params to get latest posts
        $args = [
            'posts_per_page' => $max_posts_number,
            'offset' => !is_null($offset) ? $offset : '',
            'post_type' => $post_type,
            'fields'  => 'ids',
            'post__not_in' => array_filter($posts_exclude),
        ];

        if ($category_slug !== 'tg') {
            $args['category__in'] = $cat->term_id ?? null;
        }

        // in case there isn't enough ids to be shown in locking BO, get them and count them
        $option_ids = array_unique(explode(',', $ids));
        $option_ids = array_diff($option_ids, $posts_exclude);
        $count_ids  = count($option_ids);

        if (empty($ids) || $count_ids == 0) {
            return $args;
        }

        if ($count_ids < $max_posts_number) {
            // get the posts we need
            $args['posts_per_page'] = $max_posts_number - $count_ids;
            $latest   = get_posts($args);
            $post_ids = array_map('strval', $latest);
            // prepare the array of latest posts to be merged with the locking BO ids ( we put the BO ids first in the new array )
            $option_ids = array_unique(array_merge($option_ids, $post_ids));
        }
        return array('post_type' => $post_type, 'orderby' => 'post__in', 'post__in' => $option_ids);
}

