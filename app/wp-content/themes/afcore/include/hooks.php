<?php 
add_action('after_setup_theme', 'afmm_setup');
add_action('admin_enqueue_scripts', 'core_admin_enqueue_theme_scripts');
add_action('wp_enqueue_scripts', 'core_enqueue_theme_scripts');
add_action('before_the_content', 'gallery_monetisation');
add_action('before_the_content', 'show_block_partage');
add_action('after_the_content', 'show_block_partage');
add_action('init', 'register_aftg_menus');
add_action('wp_head', 'Post_helper::print_site_config_js', 2);
add_action('after_the_excerpt', 'add_dfp_mobile_2');
/**les filtres **/
add_filter('the_content', 'update_external_links');

function afmm_setup()
{
  add_theme_support('post-thumbnails');
  add_theme_support(
    'html5',
    array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
    )
  );
}

function core_enqueue_theme_scripts()
{
  global $site_config;
  $ismobile = $site_config['ismobile'];

  if ((PROJECT_NAME == "afmm") || (PROJECT_NAME == "aftg" && is_singular('destination'))) {
    wp_enqueue_style('carousel-css', AF_THEME_DIR_URI . '/assets/stylesheets/lib/owl.carousel.min.css', array(), CACHE_VERSION_CDN);
    wp_register_script('carousel-script', AF_THEME_DIR_URI . '/assets/js/lib/owl.carousel.min.js', array('jquery'), CACHE_VERSION_CDN, true);
    wp_enqueue_script('carousel-script');
  }
  // pour le script parallax (on l'utilise en afmm & aftg);
  if ((PROJECT_NAME == "afmm" && (is_home() || is_singular())) || (PROJECT_NAME == "aftg" && (is_singular('adresse')))) {
    wp_enqueue_script('parallax-bg', AF_THEME_DIR_URI . '/assets/js/front/parallax.js', array('jquery'), CACHE_VERSION_CDN, true);
  }
  if (!$ismobile && (is_singular('adresse')  || is_singular('post') || is_singular('travel-guide'))) {
    wp_enqueue_script('read_time-js', AF_THEME_DIR_URI . '/assets/js/front/read_time.js', array('jquery'), CACHE_VERSION_CDN);
  }
  wp_enqueue_script('bootstrap-script', AF_THEME_DIR_URI . '/assets/js/lib/bootstrap.bundle.min.js', array('jquery'), CACHE_VERSION_CDN, true);
  wp_enqueue_script('isipad', AF_THEME_DIR_URI . '/assets/js/front/isipad.js', array('jquery'), CACHE_VERSION_CDN, true);
  wp_enqueue_script('ga_tracker', AF_THEME_DIR_URI . '/assets/js/front/ga_tracking.js', array('jquery'), CACHE_VERSION_CDN, true);
  // try to work with font-awesome locally but .. (tested with af_theme_dir_uri)
  wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
  wp_enqueue_script('selectize', AF_THEME_DIR_URI . '/assets/js/lib/selectize.min.js', array('jquery'), CACHE_VERSION_CDN, true);
  wp_enqueue_script('afcore-utils', AF_THEME_DIR_URI . '/assets/js/lib/afcore-utils.js', array('jquery'), CACHE_VERSION_CDN, true);
  if (is_single()) {
    wp_enqueue_script('share', AF_THEME_DIR_URI . '/assets/js/front/share.js', array('jquery'), CACHE_VERSION_CDN, true);
  }
}

function core_admin_enqueue_theme_scripts()
{
  global $pagenow, $post;
  wp_enqueue_script('single-image-uploader', AF_THEME_DIR_URI . '/assets/js/admin/image-upload.js', array('jquery'), CACHE_VERSION_CDN, true);
  wp_enqueue_style('selectize-css', AF_THEME_DIR_URI . '/assets/stylesheets/css/selectize.css', CACHE_VERSION_CDN, true);
  wp_enqueue_script('selectize', AF_THEME_DIR_URI . '/assets/js/lib/selectize.min.js', array('jquery'), CACHE_VERSION_CDN, true);
  wp_enqueue_style('selectize-bootstrap-4', AF_THEME_DIR_URI . '/assets/stylesheets/css/selectize.bootstrap4.css', CACHE_VERSION_CDN);
  wp_enqueue_script('color-weel-script', AF_THEME_DIR_URI . '/assets/js/lib/jscolor.min.js', array('jquery'));
  // add scripts and styles codemirror
  if (PROJECT_NAME == 'aftg') {
    wp_enqueue_style('codemirror-css', AF_THEME_DIR_URI . '/assets/js/vendor/codemirror/lib/codemirror.css', CACHE_VERSION_CDN);
    wp_enqueue_script('codemirror', AF_THEME_DIR_URI . '/assets/js/vendor/codemirror/lib/codemirror.js', array('jquery'), CACHE_VERSION_CDN, true);
    wp_enqueue_script('codemirror-js-mode', AF_THEME_DIR_URI . '/assets/js/vendor/codemirror/mode/javascript/javascript.js', array('jquery', 'codemirror'), CACHE_VERSION_CDN, true);
    wp_enqueue_script('codemirror-js-addon', AF_THEME_DIR_URI . '/assets/js/vendor/codemirror/addon/edit/matchbrackets.js', array('jquery', 'codemirror'), CACHE_VERSION_CDN, true);
    wp_enqueue_script('js-beautify', AF_THEME_DIR_URI .  '/assets/js/lib/beautify.min.js', array('jquery', 'codemirror'), CACHE_VERSION_CDN, true);
  }
  if (in_array($pagenow, ['post-new.php', 'post.php'])) {
    wp_enqueue_script('thumb-mobile-preview', AF_THEME_DIR_URI . '/assets/js/admin/thumb_mobile_preview.js', array('jquery'), CACHE_VERSION_CDN, true);
    $post_thumb = get_the_post_thumbnail_url($post->ID, 'full');
    wp_localize_script('thumb-mobile-preview', 'thumb_preview', array(
      'image' => $post_thumb,
      'refresh' => AF_THEME_DIR_URI . '/assets/img/refresh.png'
    ));
  }
}

function block_partage()
{
  global $post;
  global $site_config;
  $ismobile = $site_config['ismobile'] ? 'mobile' : 'desktop';
  $key = apply_filters('project_filter', PROJECT_NAME);

  $cache_key = $site_config["{$key}_cache"]['block_partager']['key'] . $post->ID . '_' . $ismobile . '_' . current_filter();
  $cache_time = $site_config["{$key}_cache"]['block_partager']['time'];
  echo_from_cache($cache_key, 'block_partager', $cache_time, function () {
    include(locate_template('template-parts/block-partager.php'));
  });
}

/**
 * show block "partagez ce contenu" before and after the content
 */
function show_block_partage()
{

  if (is_singular()) {
    block_partage();
  }
}

function register_aftg_menus()
{
  register_nav_menus(
    array(
      'header-menu' => __('Header Menu', AFMM_TERMS),
      'footer_1' => __('Footer 1', AFMM_TERMS),
      'footer_2' => __('Footer 2', AFMM_TERMS),
      'footer_3' => __('Footer 3', AFMM_TERMS),
    )
  );
}



function gallery_monetisation()
{
  if (is_singular()) {
    global $post;
    global $site_config;
    $post_type = get_post_meta($post->ID, 'post_display_type_name', true);
    if (in_array($post_type, ['DIAPO','DIAPO ACQUISITION'])) {
      ($site_config['ismobile']) ? $key_extra = 'mobile' : $key_extra = 'desktop';
      $cache_key = $site_config["afmm_cache"]['gallery_monetisation']['key'] . $post->ID . $key_extra;
      $cache_time = $site_config["afmm_cache"]['gallery_monetisation']['time'];

      //il faut pas utiliser ce cache objet sur les block non partagé entre les templates
      //echo_from_cache($cache_key, 'gallery_monetisation', $cache_time, function () {
        include(locate_template('template-parts/gallery-monetisation.php'));
      //});
    }
  }
}
function render_vignette_html($post_visiter_endroit_meta, $meta_name, $bg_color, $image, $titre, $lien, $hasText, $type)
{
  if ($post_visiter_endroit_meta[$meta_name] == 'true') {
    $visitez_endroit_bg_color_vignette = $post_visiter_endroit_meta[$bg_color];
    $visitez_endroit_image_vignette = $post_visiter_endroit_meta[$image];
    $visitez_endroit_titre_vignette = $post_visiter_endroit_meta[$titre];
    $visitez_endroit_lien_vignette = $post_visiter_endroit_meta[$lien];
    $hasLink = !empty($visitez_endroit_lien_vignette);

?>
    <div class="visitez_endroit_visuel_vignette" style="background-color:<?php echo $visitez_endroit_bg_color_vignette ?>">
      <a class="visitez_endroit_visuel_vignette_content<?php echo !($hasLink) ? '-disabled' : '' ?>" href="<?php echo ($hasLink) ? $visitez_endroit_lien_vignette : '' ?>" target="_blank">
        <?php if (!empty($visitez_endroit_image_vignette)) { ?>
          <img class="svg-white" src="<?php echo  AF_THEME_DIR_URI . '/assets/img/af-biblio/' . $type . '/' . $visitez_endroit_image_vignette; ?>" />
        <?php
        }
        ?>
        <p> <?php echo $visitez_endroit_titre_vignette; ?> </p>
      </a>
    </div>
  <?php
  }
}

function generate_utm_params($content, $source = 'RDH')
{
  $afmm_aftg  = PROJECT_NAME == 'afmm' ? 'AFM' : 'TVG';
  return 'utm_medium=AFF' . '&utm_source=' . $source . '&utm_campaign=FR_'.$afmm_aftg.'_CNV_EnVols__' . '&utm_content=' . $content . '&utm_term=';
}

//filtre le contenu des articles: ajoute à URL les params ?utm_source=LHT&utm_medium=GUIDE/OR MEDIA à tous les liens externes 
function update_external_links($content)
{
  $afmm_aftg = PROJECT_NAME == 'afmm' ? 'AFM' : 'TVG';
  $content = preg_replace('/(http|https):\/\/[^"]*?\.airfrance\.fr\/[^"]+/','$0?utm_medium=AFF&utm_source=RDC&utm_campaign=FR_'.$afmm_aftg.'_CNV_EnVols__&utm_content=LienHT&utm_term=',$content);
  return $content;
}
/* Ajouter de la position mobile aprés la fonction the_excerpt dans les deux single.php */
function add_dfp_mobile_2()
{
  if (!get_omep_val('couper_position_mobile_102_0741')) {
    echo do_shortcode("[dfp id='mobile_2']");
  }
}

/**
 * Add <meta name="keywords" content="focus keywords">.
 */
add_filter('rank_math/frontend/show_keywords', '__return_true');

function get_omep($key)
{
  global $omeps;
  return (isset($omeps[$key])) ? $omeps[$key] : '';
}

function get_omep_val($key)
{
  return (isset(Omep::$option_value[$key])) ? Omep::$option_value[$key] : '';
}

add_filter('partenaires_post_type', 'partenaires_post_type');

function partenaires_post_type()
{
  if (PROJECT_NAME == "aftg") return 'destination';
  return 'post';
}

add_filter('resize_thumb_post_type', 'resize_thumb_post_type');

function resize_thumb_post_type()
{
  if (PROJECT_NAME == "aftg") return 'adresse';
  return 'post';
}


function get_estimated_reading_time($content = '')
{
  $wpm = 265;
  $text_content = strip_shortcodes($content);   // Remove shortcodes
  $str_content = strip_tags($text_content);     // remove tags
  $word_count = str_word_count($str_content);
  $readtime = ceil($word_count / $wpm);
  $postfix = "MIN";
  $readingtime = $readtime . $postfix;
  return $readingtime;
}
add_action('read_time', 'read_time_post', 10, 1);
function read_time_post($is_diapo)
{
  global $read_time;
  $read_time_meta = get_post_meta(get_the_ID(), 'read_time', true);

  $post_thumbnail_caption = get_the_post_thumbnail_caption();
  if (!$is_diapo) { ?>
    <div class="row">
      <div class="col-md-12">
        <div class="post-read-time">
          <span class="fa fa-clock-o"></span>
          <span class="read-time"> <?php _e("TEMPS DE LECTURE", AFMM_TERMS);
                                    echo " : ";
                                    echo $read_time; ?></span>
          <?php
          if ($post_thumbnail_caption) {
          ?>
            <span class="post-copyright">&copy; <?php echo $post_thumbnail_caption; ?></span>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  <?php }
}

add_action('wp_head', 'intialise_read_time');

function intialise_read_time()
{
  if (is_single()) {
    global $read_time;
    global $read_time_title;
    $read_time = get_estimated_reading_time(get_the_content());
    $read_time_title = get_the_title();
  }
}
add_action('end_header', 'end_header');
function end_header($ismobile)
{
  if (!$ismobile && is_singular(['post','travel-guide','adresse'])) { ?>
    <div class="read-time-header-block progress"></div>
<?php }
  if (PROJECT_NAME == 'afmm') {
    include(locate_template('template-parts/forms/search_form.php'));
  }
}


add_action( 'after_setup_theme', 'theme_generate_thumbnails' );
function theme_generate_thumbnails() {
	add_image_size( 'thumb-w-300-h-280', 300, 280,true );

	add_image_size( 'thumb-w-360', 360, 9999,true );
	add_image_size( 'thumb-w-360-h-190', 350, 190,true );
	add_image_size( 'thumb-w-278-h-200', 278, 200,true );
	add_image_size( 'thumb-w-784-h-445', 784, 445,true );
	add_image_size( 'thumb-w-141-h-101', 141, 101,true );

}

add_filter('select_cropped_img', 'select_cropped_img', 10, 4);
function select_cropped_img($image_dimensions) {
    $image_size = $image_dimensions[0];
    if (array_key_exists('custom_size', $image_dimensions) || array_key_exists('original_size', $image_dimensions)) {
        $image_size = rw_is_mobile() ? $image_dimensions['custom_size'] : $image_dimensions['original_size'];
    }

    return $image_size;
//
//    if ($is_url) {
//	    return wp_get_attachment_image_url($post_id, $image_size);
//    }
//
//    return wp_get_attachment_image($post_id, $image_size, '', $args);
}

// on laisse ce filtre en afcore psk on l'utilise dans les deux sites + (embarquement.php) !!
add_filter('get_user_country_code','get_user_country_code_function');

function get_user_country_code_function()
{
  global $site_config;
  $keyLocations = PROJECT_NAME == 'afmm' ? 'afmm_user_location' : 'aftg_user_location';
  $pays_langue_info = $site_config[$keyLocations];
  $localisation_lang = $pays_langue_info == false ? 'fr' : strtolower($pays_langue_info['pays_iso_code']);
  return $localisation_lang;
}