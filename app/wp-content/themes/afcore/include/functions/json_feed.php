<?php
define('HAS_GALLERY_REGEXP' ,"/\[gallery\s*(g\_d=\"[^\"]*\")?\s*ids\s*=\s*\"?\'?((\d|,)+)\"?\'?.*\](.*?\[\/gallery\])?/im" );
define('NEW_HAS_GALLERY_REGEXP', false );

function feed_json2() {
	header('Content-type: application/json; charset=utf-8');
	if(isset($_GET['feed_posts_json'])){

			$post_type = Post_Helper::rw_get_post_types() ;
			$post__in = $_GET['feed_posts_json'] ;
			$post__in = explode(',', $post__in) ;
			$args = array(
				'post__in' => $post__in,
				'post_type' => $post_type ,
				'post_status' => array('future', 'publish'),
			);
			exclude_diapo_acquisition_posts($args);
			$posts = get_posts($args);
			foreach ($posts as  &$post) {
				$post->post_status = 'publish' ;
			}
			$json =  rw_generat_json($posts) ;
			echo  json_encode( $json ) ;
			exit();
	 
	}
	isset($_GET['count'])?$count = $_GET["count"]: $count=25;
	isset($_GET['paged'])?$paged = $_GET["paged"]: $paged=1;
	$wp_feed_args = array( 'posts_per_page' => $count, 'paged'=>$paged );
	exclude_diapo_acquisition_posts($wp_feed_args);
	$wp_feed=new WP_Query($wp_feed_args);
	$json['page'] = $paged;
	if ($wp_feed->found_posts) {
		$found_posts = $wp_feed->found_posts;
		$wp_feed->query_vars['posts_per_page']=$count ;

		$posts_per_page = $wp_feed->query_vars['posts_per_page'] ;
		$total_page =  ceil($found_posts / $posts_per_page) ;
		
		$json['total_pages'] = $total_page ;
		$json['found_posts'] = $found_posts ;
		
		$json['posts'] =  rw_generat_json($wp_feed->posts);
		
	} else {
		$json['posts'] = array();
		$json['total_pages'] = 0 ;
		$json['found_posts'] = 0 ;
	}
	
	echo json_encode($json, JSON_UNESCAPED_UNICODE);
	exit();
}
function rw_generat_json($posts){
	global $post ;
	foreach ($posts as $post) {
		setup_postdata($post) ;
		$meta_data = get_post_custom($post->ID);
		$id = (int) get_the_ID();
		$post_categories = feed_get_post_categories($post->ID);
		$cats = array();
		$single = array(
			'id'			=> $id,
			'title'			=> trim(get_the_title()) ,
			'permalink'		=> get_permalink(),
			'excerpt'		=> trim(get_the_excerpt()),
			'date'			=> get_the_date('Y-m-d H:i:s','','',false) ,
			'author'		=> trim(get_the_author()),
			'nonce'			=> wp_create_nonce("nonce_ratings_" .$id),
			'categories' 	=> $post_categories['items'],
			'categories_slugs' 	=> $post_categories['slugs'],
			'categories_names' 	=> $post_categories['names'],
			'meta_data'		=> $meta_data,
			'status'		=> get_post_status($post),
			'title_highlight'	=> get_post_meta($id, 'title_highlight', true),
		);
		$single = apply_filters('rw_generat_json_post', $single ,$post);
		if(function_exists('has_post_thumbnail') && has_post_thumbnail($id)) {
			$image_id = get_post_thumbnail_id($id);
			$single["thumbnail"] = current(wp_get_attachment_image_src($image_id));
			$single["thumbnail_popularpost"] = current(wp_get_attachment_image_src($image_id, "thumbnail_popularpost"));
			$single["thumbnail_full"] = current(wp_get_attachment_image_src($image_id, "thumbnail_full"));
			$single["thumbnail_medium"] = current(wp_get_attachment_image_src($image_id, "rw_medium"));
			$single["thumbnail_large"] = current(wp_get_attachment_image_src($image_id, "rw_large"));
			$single["thumbnail_thumb"] = current(wp_get_attachment_image_src($image_id, "thumbnail"));
		}
		$content = get_the_content();

		$galleries_export = array() ;
		if(stristr($content , "[gallery" )) {
			if(preg_match_all(HAS_GALLERY_REGEXP, $content, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					if(NEW_HAS_GALLERY_REGEXP){
						$galleries = explode(',' , get_gallery_ids($match));
					}else{
						$galleries = explode(',' , $match[2]);
					}

					foreach ($galleries as $img_id) {
						$image_url= wp_get_attachment_url($img_id);
						$img = get_post($img_id) ;
						$galleries_export[] = array(
							'title' => $img->post_title,
							'excerpt' => $img->post_excerpt,
							'url' => $image_url,
						);
					}
					$content = str_replace($match[0], '', $content);
				}
			}
		}
		$content = apply_filters('the_content', $content);
		$content = wpautop($content);

		$videos = array();
		$videos_short_code = array();
		// TODO: detect AIR R+FRANCE Videos with the [af_video] shortcode and if they are vimeo_id and youtube_id and replace them with the embed code
		
		// remmove shortcodes
		$single["content"] = strip_shortcodes(trim($content));
		$single["videos"] = $videos;
		$single["videos_short_code"] = $videos_short_code;
		if(count($galleries_export)){
			$single["galleries"] = $galleries_export;
		}

		$tags = array();
		foreach((array)get_the_tags() as $tag) {
			if(isset($tag->name)){
				$tags[] = $tag->name;
			}
		}
		$single["tags"] = array_filter($tags);
		if ($single["tags"]==null) $single["tags"]=array();

		//print_r($single);
		foreach ($single as $key => $value) {
			if ($value==null && !is_array($value) && $key!="ratings") {
				$single[$key] = "";
			}
		}

		$extra = apply_filters('extra_meta_json2' , array(), $post, $meta_data);
		if($extra){
			$single["extra"] = $extra ;
		}

		$json[] = $single;
		wp_reset_postdata();
	}
	return $json ;
}


// function for getting post categories name,slugs  and store them in array
function feed_get_post_categories($post_id){
	$categories = [
		"categories" => [],
		"names" => [],
		"slugs" => []
	];
	// get the post categories
	$post_categories = get_the_category($post_id);
	if(!empty($post_categories)){
		foreach ($post_categories as $category) {
			$categories["items"][] = $category->name;
			$categories["names"][] = $category->name;
			$categories["slugs"][] = $category->slug;
		}
	}
	return $categories;
}

function get_post_categories(){
	
	global $blog_id;

	$cats = get_the_category() ;
	$list = array('items'=> [] , 'slugs'=> [], 'names' =>[]); 
	foreach($cats as $cat){
		if($cat->category_parent == 0){	
			add_feed_category_parent($list, $cat);
		} else {
			if ($blog_id == 2) {
				add_feed_category_parent($list, $cat);
			}
			$cat_parent = get_category($cat->category_parent);
			add_feed_category_parent($list, $cat_parent) ;
		}	
		$list['slugs'][] = $cat->slug;
		$list['names'][] = trim($cat->name); 
	}
	return $list ;
}

function add_feed_category_parent(&$list, $cat){
	global $blog_id;
	if ($blog_id == 2) {
		$item = $cat->term_id;	
	} else {
		$item = trim($cat->name);
	}
	if (!in_array($item, $list['items'])) {
		$list['items'][] = $item;
	}
}
function get_gallery_ids ($matches){
	$ids = '' ;
	if(isset($matches[3])){
		$attr = shortcode_parse_atts( $matches[3] );
		$ids = isset($attr['ids'])? $attr['ids'] :'';
	}
	return $ids ;
}

function exclude_diapo_acquisition_posts(&$posts) {
	if (get_omep_val('cacher_feed_article_type_diapo_acquisition_0811')) {
		$posts['meta_query'] = array(
			'relation' => 'AND',
			array(
				'compare' => 'NOT LIKE',
				'key' => 'post_display_type_name',
				'value' => 'DIAPO ACQUISITION'
			)
		);
	}
}

add_feed('json2', 'feed_json2');