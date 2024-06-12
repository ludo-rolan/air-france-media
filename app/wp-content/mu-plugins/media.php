<?php
class  Media_Helper {
	// checkif an attachent does already exist by meta "origin_url" and if not download it and save it to the media library and return the attachment id
	public static function download_attachment_by_url($url, $description = "", $date = "") {
		$attachment_id = 0;
		$file = "";
		if (strpos($url,"https://img.static-af.com/images/")!==false) {

			$file = str_replace("https://img.static-af.com/images/","",$url);
			$file = str_replace("/","",$file);
			$file .= ".jpg";
		}
		elseif (strpos($url,".mp3")!==false) {
			$file=$url;
		}
		elseif (strpos($url,"/FR/common/")==0) {
			$url = "https://www.airfrance.fr".$url;
		}
		
		
	
		
		if(preg_match('/[^\?]+\.(jpe?g|jpe|gif|png|mp3)\b/i', $url, $matches)){
			$file = basename($url);
		}
		if(preg_match('/[^\?]+\.(svg)\b/i', $url, $matches)){
			$file = basename($url).".png";
		}
		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit', //'inherit' important!
			'meta_key' => 'origin_url',
			'meta_value' => $url
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			$query->the_post();
			$attachment_id = get_the_ID();
		}
		if ($attachment_id == 0) {
			$upload_file = wp_upload_bits($file, null, self::get_file_content($url));
			if (!$upload_file['error']) {
				$wp_filetype = wp_check_filetype( $file, null );
				$attachment  = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => sanitize_file_name( $file ),
					'post_content'   => $description,
					'post_status'    => 'inherit',
					'post_date' => $date,
				);
				// Create the attachment
				$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );
				if (!is_wp_error($attachment_id)) {
					// Include image.php
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					// Define attachment metadata
					$attach_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					// Assign metadata to attachment
					wp_update_attachment_metadata( $attachment_id, $attach_data );
					update_post_meta($attachment_id, 'origin_url', $url);
					update_post_meta($attachment_id, '_wp_attachment_image_alt', $description);
				}
			}
			else {
				print_r($upload_file['error']);
			}
		}
		return $attachment_id;
	}

	public static function get_file_content($url){
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$raw=curl_exec($ch);
		curl_close ($ch);
		return $raw;
	}

    // get attachment relative path by id
	public static function get_attachment_url($attachment_id) {
		$attachment = get_post($attachment_id);
		if (empty($attachment)) {
			return "";
		}
		$url = wp_get_attachment_url($attachment_id);
		if (empty($url)) {
			return "";
		}
		return parse_url($url, PHP_URL_PATH);
	}
}