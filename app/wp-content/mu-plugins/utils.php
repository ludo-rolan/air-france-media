<?php
class  Scripts_Utils {

	public static function read_json_file($file) {
		$json = file_get_contents($file);
		$json_data = json_decode($json, true);
		return $json_data;
	}
	public static function slugify($text) {
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		// trim
		$text = trim($text, '-');
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// lowercase
		$text = strtolower($text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		if (empty($text)) {
			return 'n-a';
		}
		return $text;
	}
}