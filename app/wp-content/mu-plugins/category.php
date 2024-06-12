<?php
class Af_Category_Helper {
	private static $category_parents_ids = array();
	private static $post_category_from_url = array();

	static function  get_post_categorie_name($category_detail)
	{
		if (isset($category_detail[0])) {
			return $category_detail[0]->name;
		} else {
			return "";
		}
	}
	static function get_post_categorie_link($category_detail)
	{
		if (isset($category_detail[0])) {
			return esc_url(get_category_link($category_detail[0]->term_id));
		} else {
			return "";
		}
	}


	/**
	 * Gets the cat slug.
	 * @param      <integer>  $cat_id 
	 * @return     string  The category's slug.
	 */
	static function get_cat_slug($cat_id) {
		$cat_id = (int) $cat_id;
		$category = get_category($cat_id);
		if (isset($category->slug)) {
			return $category->slug;
		} else {
			return "";
		}	
	}

	/**
	 * Check if a category has a parent category
	 * @param $category_id
	 * @return bool
	 */
	static function category_has_parent( $category_id ){
		$category = get_category( $category_id );
		if ( $category->category_parent > 0 ){
			return true;
		}
		return false;
	}

	/**
	 * check if currect category is child of another category by slug
	 * @param      string  $parent_cat_slug 
	 * @return     boolean  
	 */
	static function current_cat_is_child_of($parent_cat_slug){
		global $wp_query;
		if(is_category("add")){
			$id_current_cat = $wp_query->query_vars['cat'];
			$cat = get_category_by_slug($parent_cat_slug);
			if( !empty($cat) ){
				return cat_is_ancestor_of($cat->term_id,$id_current_cat);
			}
		}
		return false;
	}

	/**
	 * Get category parents' slugs 
	 * @param      int  category_id { category's id }
	 * @return     array ( array of categories parents' slugs )
	 */
	static function get_category_parents_slugs( $category_id ){
		$category_parents 		= get_category_parents($category_id, false, ':', true);
		$category_parents_array = explode(':',$category_parents);
		array_pop( $category_parents_array );
		return $category_parents_array;
	}
	/**
	 * Get top level parent category slug
	 * @param      WP_Term  category object 
	 * @return     string  top level category slug
	 */
	static function get_top_parent_category($category){
		if($category->parent != 0){
			$cat_tree = get_category_parents($category->term_id, false, ':', true);
			$top_cat = explode(':',$cat_tree);
			$slug = $top_cat[0];
		}else{
			$slug = $category->slug;
		}
		return $slug ;
	}
	static function reworld_get_category_parents_ids( $id, $separator = '/', $visited = array(), $field = 'term_id') {
		$key = $id.$separator.serialize($visited);
		if(isset(self::$category_parents_ids[$field][$key])){
			return self::$category_parents_ids[$field][$key] ;
		}
		$chain = '';
		$parent = get_term( $id, 'category' );
		if ( is_wp_error( $parent ) )
			return $parent;

		$name = $parent->$field;

		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			$chain .= self::reworld_get_category_parents_ids( $parent->parent, $separator,  $visited, $field  );
		}

		
		$chain .= $name.$separator;
		self::$category_parents_ids[$field][$key] = $chain;
		return $chain;
	}

	static function get_menu_cat_post_old($p='', $class='', $first_level=false, $parent_id=false, $href_js=false, $simple_cat=false, $attr=array(),$exclude_cat='') {
		global $post ;
		$p = ($p)? $p:$post ;
		if(!is_object($p)){
			$p = get_post($p);
		} 
		$category  = null ;
		// TODO : get rid of this get_post_category_from_url
		if( apply_filters( 'disable_category_from_url' , !$simple_cat , $p) ) {
			$category = Af_Category_Helper::get_post_category_from_url($p, $first_level);
		} 
		if($simple_cat OR !$category){
			$cat =  get_post_meta($p->ID,'_category_permalink',true) ;
			if($cat){
				$category = get_category($cat) ;	
			}else{			
				$category = get_the_category();
				if($exclude_cat!='') {
					foreach ($category as $categor) {
						if($categor->slug!=$exclude_cat) {
							$category = $categor;
							break;
						}
					}
				}else {
					$category=isset($category[0]) ? $category[0] : '';
				}
			}
		}
		return $category;
	}
	static function get_post_category_from_url($p=null, $first_level=false) {

		global $post ;
		$post_id = $p!=null?$p->ID: $post->ID;
		$post_ = $p !=null ? $p : $post;

		if(isset(self::$post_category_from_url[$post_id . $first_level])){
			return self::$post_category_from_url[$post_id . $first_level] ;
		}

		if(in_array($post_->post_status, array( 'draft', 'pending', 'auto-draft', 'future' ))){
			$link_current_page = Af_Category_Helper::get_not_publish_permalink($post_id);
		}else{
			$link_current_page = get_permalink($post_);
		}
		
		$link_current_page = str_replace(home_url('/'), '', $link_current_page);
		$link_current_page = trim($link_current_page, '/');
		$link_current_page = explode('/', $link_current_page);

		if (!$first_level) {
			$cat_part = (count($link_current_page) >= 2 && isset($link_current_page[count($link_current_page)-2]) ) ? $link_current_page[count($link_current_page)-2] : false;
		} else {
			$cat_part = array_shift($link_current_page);
		}	
		$cat_object = get_category_by_slug($cat_part);
		self::$post_category_from_url[$post_id . $first_level] = $cat_object ;
		return $cat_object;
	}

	static function get_not_publish_permalink( $post_id ) {
		require_once ABSPATH . '/wp-admin/includes/post.php';
	    list( $permalink, $postname ) = get_sample_permalink( $post_id );
	    $link = str_replace( '%postname%', $postname, $permalink );
	    return $link;
	}
	
}
