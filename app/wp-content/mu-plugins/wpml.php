<?php

class WPML_helper{

	public static function get_current_language(){
		global $sitepress;
		return $sitepress->get_current_language();
	}

	public static function get_default_language(){
		global $sitepress;
		return $sitepress->get_default_language();
	}

	public static function get_current_post_id(){
		global $sitepress;
		return $sitepress->get_current_object_id();
	}

	public static function get_current_post_type(){
		global $sitepress;
		return $sitepress( $sitepress->get_current_object_id() );
	}

	public static function get_current_post_language(){
		global $sitepress;
		return $sitepress->get_current_post_language( $sitepress->get_current_object_id() );
	}

	public static function get_post_language($post_id){
		global $sitepress;
		return $sitepress->get_current_post_language( $post_id );
	}


}