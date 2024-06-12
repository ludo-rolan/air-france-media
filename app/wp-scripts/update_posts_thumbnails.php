<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
require 'aftg_helper.php';
require_once( ABSPATH . 'wp-admin/includes/image.php' );

global $wpdb;

$options = getopt('', ['$language::']);
$language = !empty($options['language']) ? $options['language'] : 'fr';

$new_image_path = wp_upload_dir()['basedir'] . '/new_images/';
$destinations_dir = DATA_DIR . 'destinations/';
$sql = "SELECT
       		p.ID as post_id,
       		p.post_name as post_name,
       		pm.meta_value as code_iata
		FROM wp_posts p
    	LEFT JOIN wp_icl_translations wit ON wit.element_id = p.ID
		LEFT JOIN wp_postmeta pm ON pm.post_id = p.ID
		WHERE
			p.post_type = 'adresse'
			AND pm.meta_key = 'city'
			AND wit.language_code = '$language'
			;";
$results = $wpdb->get_results($sql, ARRAY_A);

$sql2 = "SELECT wp.post_id, wp.meta_value as external_id from wp_postmeta wp WHERE wp.meta_key = 'id';";
$external_ids = $wpdb->get_results($sql2, OBJECT_K);

$code_iata = '';
$dist = '';
foreach ($results as $result) {
	$post_id = $result['post_id'];

	if ($result['code_iata'] !== $code_iata) {
		$dist = Scripts_Utils::read_json_file( $destinations_dir . $result['code_iata'] . '_' . $language . '.json' );
		$code_iata = $result['code_iata'];
	}

	foreach ($dist['articles'] as $article) {
		if (isset($external_ids[$post_id]->external_id) && $article['id'] === $external_ids[$post_id]->external_id) {
			$image_alt = $article['content']['mainPicture']['accessibilityDescription'] ?? "" ;
		}
	}

	$imgs_dir = $new_image_path . $result['code_iata'] . '/' . $result['post_name'] . '/';
	// if folder has images inside
	if (count(glob("$imgs_dir/*")) !== 0) {
		$old_image_id = get_post_thumbnail_id($post_id);
		// scan directory and get ride of "." ".."
		$new_img = array_slice(scandir($imgs_dir), 2);
		// get first image
		$new_img_url = $imgs_dir . $new_img[1];
		$bits        = file_get_contents($new_img_url);

		$upload_file = wp_upload_bits(basename($new_img_url), null, $bits);
		if (!$upload_file['error']) {
			$filetype    = wp_check_filetype( basename( $new_img_url ), null );
			$attachment  = array(
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $new_img_url ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			$attach_id   = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			update_post_meta($attach_id, 'origin_url', $upload_file['file']);
			update_post_meta($attach_id, '_wp_attachment_image_alt', $image_alt);
			update_post_meta($attach_id, '_old_image', $old_image_id);
			set_post_thumbnail( $post_id, $attach_id );
//			wp_delete_attachment($old_image_id);
			println_flush( $code_iata . " " . $post_id . " post image has been updated" );
		}
	}
}
