<?php
class MediaTax
{
    private static $image;
    private static $attributs;
    private static $taxonomy;
    public static  function register()
    {
        global $site_config;
        self::$taxonomy = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '';
        self::$image =  isset($_GET['taxonomy']) ? $_GET['taxonomy'] . '-image-id' : '';
        self::$attributs = [self::$taxonomy . '-image-id'];
        foreach ($site_config['taxonomies_with_images'] as $taxonomy) {
            add_action('admin_enqueue_scripts', [self::class, 'taxonomy_media_uploader_enqueue']);
            add_action($taxonomy . '_add_form_fields', [self::class, 'add_term_taxonomy'], 10, 2);
            add_action('edit_' . $taxonomy, [self::class, 'updated_taxonomy_media'], 10, 2);
            add_action('created_' . $taxonomy, [self::class, 'save_taxonomy_media'], 10, 2);
            add_action($taxonomy . '_edit_form_fields', [self::class, 'edit_term_media'], 10, 2);
        }
    }
    static function taxonomy_media_uploader_enqueue($suffix)
    {
        global $site_config;
        if (!isset($_GET['taxonomy'])) return;
        if (in_array($_GET['taxonomy'], $site_config['taxonomies_with_images'])) {
            wp_enqueue_media();
            wp_enqueue_script($_GET['taxonomy'] . '-image', AF_THEME_DIR_URI . '/assets/js/admin/taxonomies.js', array('jquery'), null, true);
            wp_localize_script(
                $_GET['taxonomy'] . "-image",
                "taxonomy_object",
                array(
                    'taxonomy' => $_GET['taxonomy']
                )
            );
        }
    }

    static function buttons($class)
    {
        $btn = $class . "_button";
        $remove = $class . "_remove";
?>
        <p>
            <input type="button" class="button button-secondary  <?php echo $btn ?>" id="<?php echo $btn ?>" name="<?php echo $btn ?>" value="<?php _e('Ajouter ', 'hero-theme', AFMM_TERMS); ?>" />
            <input type="button" class="button button-secondary <?php echo $remove ?>" id="<?php echo $remove ?>" name="<?php echo $remove ?>" value="<?php _e('Supprimer ', 'hero-theme', AFMM_TERMS); ?>" />
        </p>
    <?php
    }
    static function media_input($name, $type, $class, $wrapper)
    {
    ?>
        <div class="form-field term-group">
            <label><?php _e($name, 'hero-theme', AFMM_TERMS); ?></label>
            <input type="hidden" id="<?php echo $type ?>" name="<?php echo $type ?>" class="custom_media_url" value="">
            <div id="<?php echo $wrapper ?>"></div>
            <?php self::buttons($class); ?>
        </div>

    <?php
    }
    public static function add_term_taxonomy($taxonomy)
    {
        self::media_input('Visuel', self::$image, "ct_tax_media", $taxonomy . "-image-wrapper");
    }
    public static function media_hidden_input($term, $name, $type)
    {
    ?>
        <th scope="row">
            <label for="<?php echo $type ?>"><?php echo $name ?></label>
        </th>
        <?php $media_id = get_term_meta($term->term_id,  $type, true); ?>
        <input type="hidden" id="<?php echo $type ?>" name="<?php echo $type ?>" value="<?php echo $media_id; ?>">
    <?php
        return $media_id;
    }
    public static function updated_taxonomy_media($term_id, $tt_id)
    {
        foreach (self::$attributs as $value) {
            if (isset($_POST[$value]) && '' !== $_POST[$value]) {
                $media = $_POST[$value];
                update_term_meta($term_id, $value, $media);
            } else {
                update_term_meta($term_id, $value, '');
            }
        }
    }
    public static function save_taxonomy_media($term_id, $tt_id)
    {
        foreach (self::$attributs as $value) {
            if (isset($_POST[$value]) && '' !== $_POST[$value]) {
                $media = $_POST[$value];
                add_term_meta($term_id, $value, $media, true);
            }
        }
    }
    public static function edit_term_media($term, $taxonomy)
    {
    ?>
        <tr class="form-field term-group-wrap">

            <?php $image_id =  self::media_hidden_input($term, 'Visuel', self::$image) ?>
            <td>
                <div id="<?php echo $taxonomy ?>-image-wrapper">
                    <?php if ($image_id) { ?>
                        <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                    <?php } ?>
                </div>
                <?php self::buttons("ct_tax_media"); ?>
            </td>
        </tr>

<?php
    }
}
MediaTax::register();
