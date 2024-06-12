<?php


add_action('wp_enqueue_scripts', 'enqueue_theme_styles');
add_action('init', 'register_afmm_menus');
add_action('after_the_content', 'show_edisound_block_after_post_content');
add_action('admin_enqueue_scripts', 'admin_enqueue_theme_scripts');
add_action('wp_enqueue_scripts', 'category_enqueue_script');
add_action( 'save_post', 'save_edisound_category',999 );
add_action( 'save_post', 'save_video_category' );
add_action('wp_enqueue_scripts', 'single_enqueue_script');
add_action('wp_enqueue_scripts', 'animations_enqueue_script');

function enqueue_theme_styles()
{
  global $site_config;
  $is_home=is_home();

  wp_register_script("search", AF_THEME_DIR_URI . '/assets/js/front/search.js', array('jquery'),CACHE_VERSION_CDN,true);
  wp_localize_script('search', 'search_data', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('autocomplete-destinations')
  ));
  wp_enqueue_script('search');

  if(is_single() || is_category()){
    wp_enqueue_script('plyr',AF_THEME_DIR_URI . '/assets/js/lib/plyr.min.js', array( 'jquery' ),CACHE_VERSION_CDN );
    wp_enqueue_style('plyr',AF_THEME_DIR_URI . '/assets/stylesheets/css/plyr.css',array(),CACHE_VERSION_CDN);
  }
  wp_enqueue_style('main', STYLESHEET_DIR_URI . '/assets/stylesheets/css/main.min.css',array(),CACHE_VERSION_CDN);
  wp_enqueue_script( 'carousel-script', STYLESHEET_DIR_URI . '/assets/js/lib/owl.carousel.min.js', array( 'jquery' ),CACHE_VERSION_CDN,true );
  if (!$is_home) {
    wp_enqueue_script( 'avion-script', STYLESHEET_DIR_URI . '/assets/js/front/avion_carousel.js', array( 'jquery' ),CACHE_VERSION_CDN,true );
  }
  if($is_home){
  wp_enqueue_script( 'podcast-script', STYLESHEET_DIR_URI . '/assets/js/front/podcast_carousel.js', array( 'jquery' ),CACHE_VERSION_CDN,true );
  wp_enqueue_script( 'video-script', STYLESHEET_DIR_URI . '/assets/js/front/video_carousel.js', array( 'jquery' ),CACHE_VERSION_CDN,true );
  wp_enqueue_script( 'embarquement-form', AF_THEME_DIR_URI . '/assets/js/front/embarquement_form.js', array( 'jquery','afcore-utils' ), CACHE_VERSION_CDN,true );
  wp_localize_script('embarquement-form', 'utm_params', array(
    'utm_params' => generate_utm_params('Sidebar', 'BKT')
  ));
  }
  wp_enqueue_script( 'main-script', STYLESHEET_DIR_URI . '/assets/js/front/main.js', array('jquery','carousel-script'),CACHE_VERSION_CDN,true  );
  wp_enqueue_script( 'batch-tag-loader', STYLESHEET_DIR_URI . '/assets/js/front/batch-tag-loader.js', array('jquery'),CACHE_VERSION_CDN,true);
  wp_localize_script('batch-tag-loader', 'batchLoaderArgs', array(
    'pays' => isset($site_config['afmm_user_location']['Libellé Pays FR']) ? $site_config['afmm_user_location']['Libellé Pays FR'] : 'FRANCE',
    'langue' => $site_config['langue_selectionner'],
    'preprod' => (IS_PREPROD ? 'true' : 'false'),
  ));
  if(is_page('inscription-newsletter')){
    add_action('wp_footer', 'add_nl_scripts');
    wp_enqueue_script('nl_validate_af', STYLESHEET_DIR_URI . '/assets/js/lib/newsletter/validate.min.js', array(), CACHE_VERSION_CDN, true );
    wp_enqueue_script('nl_inputmask_af', STYLESHEET_DIR_URI . '/assets/js/lib/newsletter/inputmask.min.js', array(), CACHE_VERSION_CDN, true );
	  wp_enqueue_script('nl_main_js_af', STYLESHEET_DIR_URI . '/assets/js/lib/newsletter/newsletter_main.js', array('nl_validate_af','nl_inputmask_af'), CACHE_VERSION_CDN, true );
  }
}
function add_nl_scripts() {
	wp_enqueue_style( 'nl_main_af',STYLESHEET_DIR_URI . '/assets/stylesheets/lib/newsletter.css', array(), CACHE_VERSION_CDN );
}
function category_enqueue_script()
{
  $term = get_queried_object();
  if ($term && is_category()) {
    wp_register_script('category-js', STYLESHEET_DIR_URI . '/assets/js/front/category.js', array('jquery'), CACHE_VERSION_CDN); // Custom scripts
    wp_enqueue_script('category-js');
  }
}
function single_enqueue_script()
{
  global $site_config,$post;
  
  if (is_single()) {
    $post_type = get_post_meta($post->ID, 'post_display_type_name', true);
    $scroll_enabled = get_omep_val('enlever_retour_haut_page_0695') === '1';
    ($post_type == 'DIAPO')? $is_diapo=true: $is_diapo=false;
    wp_enqueue_script( 'gallery-script', STYLESHEET_DIR_URI . '/assets/js/front/gallery_carousel.js', array( 'jquery' ),CACHE_VERSION_CDN,true );
    wp_localize_script('gallery-script', 'arrows', array(
      'leftArrow' => STYLESHEET_DIR_URI . '/assets/img/arrow-left.png',
      'rightArrow' => STYLESHEET_DIR_URI . '/assets/img/arrow-right.png',
      'is_diapo'  => $is_diapo,
      'is_scroll_enabled' => $scroll_enabled,
    ));
  }
}
function register_afmm_menus() {
    register_nav_menus(
      array(
        'header-menu' => __( 'Header Menu', AFMM_TERMS ),
        'footer1' => __( 'Footer 1', AFMM_TERMS ),
        'footer2' => __( 'Footer 2', AFMM_TERMS ),
        'footer3' => __( 'Footer 3', AFMM_TERMS),
      )
    );
}
function admin_enqueue_theme_scripts() {
  global $pagenow;
  if (is_admin() &&  in_array($pagenow ,['post-new.php','post.php'] )){
    wp_enqueue_script('meta_pinned_address',STYLESHEET_DIR_URI . '/assets/js/admin/pinned_address_metaboxes.js', array('jquery'),CACHE_VERSION_CDN);
}
}

add_action('edit_category_form_fields', 'add_meapostid_field_category_meta');
function add_meapostid_field_category_meta ($tag)
{
    $t_id = $tag->term_id;
    add_action('admin_footer', function() use($t_id){
        enqueue_mea_scripts($t_id);
    });
    $post_id = get_option("category_$t_id");
    $post_title = get_the_title($post_id);
    $term_metas = get_term_meta($t_id);
    $video_mea = array(
      '_vimeo_id' => array('Vimeo', ''),
      '_youtube_id' => array('Youtube', '')
    );
    if(!empty($term_metas)){
      if(array_key_exists('_vimeo_id', $term_metas)){
        $video_mea['_vimeo_id'][1] = $term_metas['_vimeo_id'][0];
      }
      if(array_key_exists('_youtube_id', $term_metas)){
        $video_mea['_youtube_id'][1] = $term_metas['_youtube_id'][0];
      }
    }
?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_page_post"><?php _e('Article MEA', AFMM_TERMS); ?></label></th>
        <td>
            <input type="text" name="keyword" id="keyword" onkeyup="fetch_mea()" value="<?php echo $post_title ? $post_title : ''; ?>"></input>
            <div id="datafetch" style="padding:0 28px 20px 0"></div>
            <span class="description"><?php _e('Titre du poste MEA ', AFMM_TERMS); ?></span>
        </td>
    </tr>
    <tr class="form-field" style="visibility: hidden;">
        <th scope="row" valign="top"><label for="cat_page_id"><?php _e('Id MEA', AFMM_TERMS); ?></label></th>
        <td>
            <input type="text" name="post_id" id="post_id" value="<?php echo $post_id ? $post_id : ''; ?> "><br />
            <span class="description"><?php _e('collez l\'id de l\'article selectionné dans cette case', AFMM_TERMS); ?></span>
        </td>
    </tr>
    <?php 
    foreach ($video_mea as $key => $video_array) {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_page<?php echo $key ?>"><?php _e("ID vidéo $video_array[0] MEA", AFMM_TERMS); ?></label></th>
        <td>
            <input type="text" name="cat_page<?php echo $key ?>" id="cat_page<?php echo $key ?>" value="<?php echo $video_array[1] ?>"></input>
        </td>
    </tr>
    <?php 
    }
}
function enqueue_mea_scripts($t_id)
{
    if (is_admin()){
        wp_register_script("post_mea_search", STYLESHEET_DIR_URI . '/assets/js/admin/post_mea_search.js', array('jquery'),CACHE_VERSION_CDN);
        wp_localize_script('post_mea_search', 'data', array('ajax_url' => admin_url('admin-ajax.php'),'term_id' => $t_id));
        wp_enqueue_script('post_mea_search');
    }
}
add_action('edited_category', 'save_category_fields');
function save_category_fields($term_id)
{
    $post_id = get_option("category_$term_id");
    if (!$post_id) {
        add_option("category_$term_id");
        $post_id = '';
    }
    $key = 'post_id';
    if (isset($_POST[$key])) {
        $post_id = $_POST[$key];
    }
    update_option("category_$term_id", $post_id);
    if (isset($_POST['cat_page_vimeo_id'])) update_term_meta($term_id, '_vimeo_id', $_POST['cat_page_vimeo_id']);
    if (isset($_POST['cat_page_youtube_id'])) update_term_meta($term_id, '_youtube_id', $_POST['cat_page_youtube_id']);
}




function get_breadcrumb()
{
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = '&rsaquo;'; // delimiter between crumbs
    $home = __('Accueil', AFMM_TERMS); // text for the 'Home' link
    $showCurrent = 0; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb

    global $post,$site_config;
    global $wp_query;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div class="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
        }
    } elseif (is_search()) {
      echo '<div class="crumbs"><a href="' . $homeLink . '">' . $home . '</a><div class="page-name" ><a href="" >'. __('résultats de recherche', AFMM_TERMS) .'</a></div>' ;
    } else {
        echo '<div class="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $cat = get_queried_object();
            if( $cat->parent != 0) {
              $cat_parent = get_term($cat->parent, 'category');
              echo  '<a href="' . get_term_link($cat_parent->term_id, 'category') . '">' . $cat_parent->name . '</a>';
            }
            echo '<h2 class="cat_name">'.$cat->name. '</h2></div>';
        }elseif (is_single() && !is_attachment()) {
          $primary_term = get_post_meta($post->ID,'rank_math_primary_category',true);
          $term_perents = get_term_parents_list($primary_term,'category',['separator'=>';']);
          $term_parents_list = $primary_term !="" ? explode(';',$term_perents) : false ;
          // get_term_parents_list after explode by separator always has as last item an empty => ''
          // so lets remove the last item ;)
          $term_parents_list ? array_pop($term_parents_list) : false;

          if($term_parents_list){
            foreach($term_parents_list as $term_){
              echo $term_;
              if( ($term_ != end($term_parents_list)) ){
                echo " ".$delimiter;
              }
            }
          }
        }elseif (is_page('inscription-newsletter') ) {
          echo '<a href="'.get_permalink().'" >'.get_the_title().'</a>' ;
        }elseif (is_page() ) {
          echo '<div class="page-name-prefixed" ><a href="'.get_permalink().'" >'.get_the_title().'</a></div>' ;
        }
        echo '</div>';
    }
}


function show_edisound_block_after_post_content()
{
  global $post;
  $edisound    = get_post_meta(get_the_ID(),'edisound',true);
  if(!empty($edisound['edisound_active']) && $edisound['edisound_active'] == "true")
  {
    $meta_vide = empty ($edisound['edisound_data_pid'])
              && empty ($edisound['edisound_placement_id']);

    if (!$meta_vide) {
      $data_pid     = $edisound['edisound_data_pid'];
      $data_gid     = $edisound['edisound_data_gid'];
      $placement_id = $edisound['edisound_placement_id'];
    }else
    {
      $data_pid     = get_option('podcast_option_data_pid');
      $data_gid     = get_option('podcast_option_data_gid');
      $placement_id = get_option('podcast_option_placement_id');
    }

    echo do_shortcode("[edisound placement_id=\"$placement_id\" data_pid=\"$data_pid\" data_gid=\"$data_gid\"]");
  }
}
// add category if edisound enabled on save post
function save_edisound_category( $post_id ) {
	$edisound          = get_post_meta( $post_id, 'edisound', true );
	$edisound_category = get_category_by_slug( 'podcast' );
	$post_categories   = wp_get_post_categories( $post_id );
	$cats              = array();
	foreach ( $post_categories as $c ) {
		$cat    = get_category( $c );
		$cats[] = $cat->term_id;
	}
	if ( $edisound && $edisound['edisound_active'] == 'true' ) {
		if ( ! in_array( $edisound_category->term_id, $cats ) ) {
			wp_set_post_categories( $post_id, array( $edisound_category->term_id ), true );
		}
	} else {
		if ( in_array( $edisound_category->term_id, $cats ) ) {
			unset( $cats[ array_search( $edisound_category->term_id, $cats ) ] );
			wp_set_post_categories( $post_id, $cats );
		}
	}
}

// add video category if post content contains [af_video] shortcode and video is enabled on save post
function save_video_category( $post_id ) {
	$content         = get_post_field( 'post_content', $post_id );
	$video_category  = get_category_by_slug( 'video' );
	$post_categories = wp_get_post_categories( $post_id );
	$cats            = array();
	foreach ( $post_categories as $c ) {
		$cat    = get_category( $c );
		$cats[] = $cat->term_id;
	}
	//check if content has [af_video] shortcode
	if ( has_shortcode( $content, 'af_video' ) ) {
		if ( ! in_array( $video_category->term_id, $cats ) ) {
			wp_set_post_categories( $post_id, array( $video_category->term_id ), true );
		}
	} else {
		if ( in_array( $video_category->term_id, $cats ) ) {
			unset( $cats[ array_search( $video_category->term_id, $cats ) ] );
			wp_set_post_categories( $post_id, $cats );
		}
	}
}

//TODO: specialise classes for different pages/categories by hooking into the body_class filter

// action that displays the pagination of category pages
add_action('afmm_pagination', 'show_afmm_pagination', 10, 2);
function show_afmm_pagination($query, $paged) {
   echo af_pagination::show_pagination($query, $paged);
}

//TODO: specialise classes for different pages/categories by hooking into the body_class filter

function animations_enqueue_script()
{
  if (is_home() || is_category()) {
    wp_enqueue_script('animations-js', STYLESHEET_DIR_URI . '/assets/js/front/animations.js', array('jquery', 'afcore-utils'), CACHE_VERSION_CDN); // Custom scripts
  }
  if(is_single()){
    wp_enqueue_script('animations-article', STYLESHEET_DIR_URI . '/assets/js/front/animations_article.js', array('jquery','afcore-utils'), CACHE_VERSION_CDN); // Custom scripts
  }
}
// hide diapo monétisation post  from HP & HOME RUBRIQUE
/*function hide_diapo_post( $query ) {
  if( !is_admin() && !$query->is_main_query() ) {
    if ((isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'post' )|| $query->is_search ) {
      $query->set( 'meta_query', array(
        array(
            'key'     => 'post_display_type_name',
            'compare' => 'NOT LIKE',
            'value'   => "DIAPO",
        )
    ) );
    }
  }
}
add_action( 'pre_get_posts', "hide_diapo_post" );*/

// hide diapo outbrain post  from HP & HOME RUBRIQUE
function hide_diapo_acquisition_post( $query ) {
    if ( get_omep_val('cacher_article_type_diapo_acquisition_0811')) {
	    if (!is_admin() && !$query->is_main_query()) {
		    if ((isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'post' ) || $query->is_category ) {
			    $query->set( 'meta_query', array(
				    array(
					    'key'     => 'post_display_type_name',
					    'compare' => 'NOT LIKE',
					    'value'   => "DIAPO ACQUISITION",
				    )
			    ) );
		    }
	    }
    }
}
add_action( 'pre_get_posts', "hide_diapo_acquisition_post" );

// ajouter la langue pour les fields de hp_options ;
add_filter('get_fields_deps_lang','get_fields_deps_lang');
function get_fields_deps_lang($filed_name)
{
  return ICL_LANGUAGE_CODE .'_'. $filed_name;
}
if(class_exists( 'RankMath' )){
  add_filter( 'rank_math/frontend/robots', function ( $robots ) {
    
    $id=get_the_ID();
    $display_type=get_post_meta( $id, "post_display_type_name", true);
    if ( $display_type=="DIAPO ACQUISITION") { 
      unset( $robots['index'] );
      $robots['noindex'] = 'noindex';
      unset( $robots['follow'] );
      $robots['nofollow'] = 'nofollow';
    }
    return $robots;
  } );
}
