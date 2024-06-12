<?php
add_action( 'save_post', 'save_destination_taxonomy', 10, 3 );

add_action('admin_enqueue_scripts', 'aftg_admin_enqueue_theme_scripts');
add_action('wp_enqueue_scripts', 'aftg_enqueue_theme_scripts');
add_action('aftg_pagination', 'show_aftg_pagination', 10, 2);
add_action('bandeau_logos_partenaires', 'bandeau_logos_partenaires');

add_action('wp_head', 'show_booking_flight');

add_filter( 'query_vars', 'themeslug_query_vars' );
add_filter( 'unserialise_metabox', 'unserialise_metabox', 10, 3 );

add_action('updated_post_meta', 'check_meta_change', 0, 4);
function check_meta_change($meta_id, $post_id, $meta_key, $meta_value)
{
	if ('geo_coordonees' == $meta_key) {
		update_post_meta($post_id, 'localisation_map', '');
	}
}

function aftg_enqueue_theme_scripts() {
	if(is_home()) {
		wp_enqueue_script('multi-select-filter', STYLESHEET_DIR_URI . '/assets/js/front/multi_select_filter.js',array(),CACHE_VERSION_CDN,true );
		wp_enqueue_script('block-filter', STYLESHEET_DIR_URI . '/assets/js/front/block-filter.js',array(),CACHE_VERSION_CDN,true );

		wp_enqueue_script('autocomplete-script', STYLESHEET_DIR_URI . '/assets/js/front/autocomplete.js',array('jquery', 'jquery-ui-autocomplete'),CACHE_VERSION_CDN,true );
	}
	wp_enqueue_style('main', STYLESHEET_DIR_URI . '/assets/stylesheets/css/main.min.css',array(),CACHE_VERSION_CDN);
	wp_enqueue_script('animations-tg-js', STYLESHEET_DIR_URI . '/assets/js/front/animations-tg.js', array('jquery', 'afcore-utils'), CACHE_VERSION_CDN); // Custom scripts
	if(isset($_GET['info_pratiques'])&& is_singular('destination')){
		wp_enqueue_script('infos-pratiques-js', STYLESHEET_DIR_URI . '/assets/js/front/infos_pratiques.js', array('jquery'), CACHE_VERSION_CDN); 
	}
	wp_enqueue_script( 'main-script', STYLESHEET_DIR_URI . '/assets/js/front/main.js', array('jquery','carousel-script'),CACHE_VERSION_CDN,true  );
	if(is_page()) {
	wp_enqueue_script('block-adresse', STYLESHEET_DIR_URI . '/assets/js/front/block-adresse.js',array(),CACHE_VERSION_CDN,true );
	}

	if(is_singular('destination')) {
		wp_enqueue_script('breadcrumbs-active-class', STYLESHEET_DIR_URI . '/assets/js/front/breadcrumbs-active-class.js', array(), CACHE_VERSION_CDN, true);
		wp_register_script('carousel', STYLESHEET_DIR_URI . '/assets/js/front/carousel.js', array('jquery'), CACHE_VERSION_CDN, true);
		wp_enqueue_script('carousel');
		wp_enqueue_script( 'embarquement-form', AF_THEME_DIR_URI . '/assets/js/front/embarquement_form.js', array( 'jquery','afcore-utils' ), CACHE_VERSION_CDN,true );
		wp_localize_script('embarquement-form', 'utm_params', array(
			'utm_params' => generate_utm_params('Sidebar', 'BKT')
		  ));
		wp_enqueue_script('navbar', STYLESHEET_DIR_URI . '/assets/js/front/navbar.js', array('jquery'), CACHE_VERSION_CDN, true);
	}
	if(is_singular('destination') || is_tax('destinations')){
		wp_enqueue_script('expand-bloc', STYLESHEET_DIR_URI . '/assets/js/front/expand-bloc.js', array(), CACHE_VERSION_CDN, true);
	}

	if(is_singular('adresse')){
		wp_enqueue_script('plyr',AF_THEME_DIR_URI . '/assets/js/lib/plyr.min.js', array( 'jquery' ),CACHE_VERSION_CDN );
		wp_enqueue_style('plyr',AF_THEME_DIR_URI . '/assets/stylesheets/css/plyr.css',array(),CACHE_VERSION_CDN);
	}

	if(is_archive()){
		wp_enqueue_script('breadcrumbs-active-class', STYLESHEET_DIR_URI . '/assets/js/front/breadcrumbs-active-class.js', array(), CACHE_VERSION_CDN, true);
	}

	if (is_home() || is_page_template('page-listing-destination.php') ) {
		wp_register_script("destinations-results", STYLESHEET_DIR_URI . '/assets/js/front/destinations_results.js', array('jquery'),CACHE_VERSION_CDN,true);
		wp_localize_script('destinations-results', 'destinations_results', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('destinations-result')
		));
		wp_enqueue_script('destinations-results');
	}
	wp_enqueue_script('carnet_voyage', STYLESHEET_DIR_URI . '/assets/js/front/carnet-voyage.js',array('jquery','afcore-utils'),CACHE_VERSION_CDN,true );
	if(is_page_template('page-credits-photo.php')){
		wp_enqueue_script('credit_photos', STYLESHEET_DIR_URI . '/assets/js/front/credit_photo.js',array('jquery'),CACHE_VERSION_CDN,true );
	}
}

function save_destination_taxonomy( $post_id, $post, $update ) {
	if (class_exists('SitePress')) {
		global $sitepress;
		$sitepress->switch_lang(WPML_helper::get_current_language());
	}
	if (
		'destination' == $post->post_type
		&&
		'publish' == $post->post_status
		&&
		!empty($post->post_title)
	) {
		$destination_slug = $post->post_name;
		$destination_name = $post->post_title;
		$destination_taxonomy = false;
		if ($update){
			$destination_taxonomy = get_the_terms( $post_id, 'destinations');
			$destination_taxonomy = $destination_taxonomy[0]??false;
		}
		if ($destination_taxonomy){
			$destination_taxonomy_id = $destination_taxonomy->term_id;
			wp_update_term(
				$destination_taxonomy_id,
				'destinations',
				array(
					'name' => $destination_name,
					'slug' => $destination_slug
				)
			);
			wp_set_object_terms( $post_id, $destination_taxonomy->term_id, 'destinations' );
		}else {
			// check if the destination already exists by slug
			$destination_taxonomy = get_term_by( 'slug', $destination_slug, 'destinations' );
			if ($destination_taxonomy){
				wp_set_object_terms( $post_id, $destination_taxonomy->term_id, 'destinations' );
			}
		}
	}
}
function aftg_admin_enqueue_theme_scripts() {
	wp_enqueue_style('aftg-admin-css', STYLESHEET_DIR_URI . '/assets/stylesheets/css/admin/admin.css', CACHE_VERSION_CDN, true);
	wp_enqueue_script('meta_address_carte',STYLESHEET_DIR_URI . '/assets/js/admin/carte_address_metaboxes.js', array('jquery'),CACHE_VERSION_CDN);
}
function show_booking_flight()
{
    if(PROJECT_NAME == 'aftg') {
	include(locate_template('template-parts/home/book_fly_template.php'));
	}
}

function generate_vol_url($depart, $destination) {
	$localisation_lang = apply_filters('get_user_country_code',NULL);
	$url_redirection_af = 'https://wwws.airfrance.fr/exchange/deeplink?language='.ICL_LANGUAGE_CODE .'&country='.$localisation_lang.'&target=/search';
	$url_redirection_af .= "&cabinClass=ECONOMY&connections=" . $depart . ":C>"
	. $destination . ":C-" . $destination  . ":C>" . $depart . ":C"	. "&bookingFlow=LEISURE";
	return $url_redirection_af;
}

function themeslug_query_vars( $qvars ) {
	// destination => dest
	// filtre => flt
	// on ne peut pas les ajouter comme paramètres donc je choisis cette nomination
    $qvars[] = 'destination_id';
    $qvars[] = 'af_filtres';
    return $qvars;
}

function show_aftg_pagination($query, $paged) {
	echo af_pagination::show_pagination($query, $paged);
}
//TODO: Add a meta field to the destination taxonomy to store the id of the destination post
function unserialise_metabox($post_metas,$metabox, $meta){
	$unseralized=maybe_unserialize($post_metas[$metabox][0]);
	if($unseralized && isset($unseralized[$meta])){
		if($result=explode(',',$unseralized[$meta])){
			return isset($result[0]) ? $result[0] :'';
		}
	}
	return '';
}

function bandeau_logos_partenaires () {
	$bandeau_active = get_omep_val('activer_bandeau_partenaires_0696') === '1';
	if($bandeau_active) {
	?>
		<div class="bloc">
			<?php include locate_template('/template-parts/block_bandeau_partners.php'); ?>
		</div>
	<?php 
	}
}

add_action( 'filtre_edit_form_fields', 'edit_term_fields_order', 10, 2 );
function edit_term_fields_order( $term, $taxonomy ) {
	$tax_count = wp_count_terms(['taxonomy' => $taxonomy, 'parent'=> 0]);
    $value = (int) get_term_meta( $term->term_id, 'filter_order_number', true );
    $value = !empty($value) ? $value : 1;
	?>
    <tr class="form-field">
        <th>
            <label>Filtre Order </label>
        </th>
        <td>
            <select name="filter_order_number" id="filter_order_number" >
                <option selected value="<?php esc_attr( $value ) ?>">Position <?php echo $value ?> </option>
		        <?php
		        for ($i = 1; $i < $tax_count + 1; $i++) {
		            if ($i !== $value) {
			        ?>
                    <option value="<?php echo $i ?>">Position <?php echo $i ?></option>
			        <?php
		            }
                }
		        ?>
            </select>
        </td>
    </tr>
	<?php
}

add_action( 'created_filtre', 'filtre_tax_term_fields' );
add_action( 'edited_filtre', 'filtre_tax_term_fields' );
function filtre_tax_term_fields( $term_id ) {
    $term_value = !empty($_POST[ 'filter_order_number' ]) ? $_POST[ 'filter_order_number' ] : 1;
	    update_term_meta(
		$term_id,
		'filter_order_number',
		sanitize_text_field($term_value)
	);
}