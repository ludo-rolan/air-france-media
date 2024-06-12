<?php
class relay_42 extends rw_partner {

	/**
	 * Ce script est inséré sur l’ensemble des pages
	 *
	 * */


	function relay_42_implementation(){
		global $post;
		$post_name = get_post_name();
		$properties =  $this->get_param('properties');
		$properties['website_country']=$properties['Country'];
		unset($properties['Country']);
		if(is_singular(['post','travel-guide'])){
			$properties=array_merge($properties,['country'=>'','city'=>'','airport'=>'','categories'=>[]]);
			$destinations=get_the_terms($post->ID,'destinations');
			$categories = get_the_category();
			foreach($categories as $cat){
				$properties['categories'][]= $cat->slug;
			}
			if($destinations && count($destinations) ){
				
				foreach($destinations as $key=>$dest){
					if($dest->parent==0){
						unset($destinations[$key]);
					}
					else {
						$code=get_term_meta($dest->term_id,'code',true);
						$count=strlen($code);
						if($count==3){
							$properties['city']=$dest->slug;
							$properties['airport']=$code;
						}
						else if($count==2){
							$properties['country']=$dest->slug;
						}
					}
				}
			}
		}
		$properties = json_encode($properties);
			$script = <<<RELAY


			<script type="didomi/javascript" data-vendor="iab:631">(function(a,d,e,b,f,c,s){a[b]=a[b]||function(){a[b].q.push(arguments);};
			a[b].q=[];c=d.createElement(e);c.async=1;c.src="//tdn.r42tag.com/lib/"+f+".js";
			s=d.getElementsByTagName(e)[0];s.parentNode.insertBefore(c,s);})
			(window,document,"script","_st", "1205-v1");
			// Add additional settings here (optional)
			_st('setPageStructure', 'reworld', '$post_name' ); 
			_st('addTagProperties',$properties);
			_st("loadTags");</script> 

RELAY;

			echo $script ;
	}
}

function get_post_name(){
	global $wp_query;
	$page ='';
	if ( is_front_page() || is_home() ) {
		$page = 'page-home';
	}
	if ( is_search() ) {
		$page = 'page-search';
	}
	if ( is_attachment() ) {
		$page = 'page-attachment';
	}
	if ( is_404() ) {
		$page = 'page-error404';
	}

	if ( is_singular() || is_single()) {
		$post_id   = $wp_query->get_queried_object_id();
		$post      = $wp_query->get_queried_object();
		$page = $post->post_name;
		if ( is_attachment() ) {
			$mime_type   = get_post_mime_type( $post_id );
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
			$page   = 'attachmentid-' . $post_id;
		} elseif ( is_page() ) {
			$page_id = $wp_query->get_queried_object_id();
			$post = get_post( $page_id );
			$page =  'page-'.$post->post_name;
		}
	} elseif ( is_archive() ) {
		if ( is_post_type_archive() ) {
			$page = 'post-type-archive';
			$post_type = get_query_var( 'post_type' );
			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}
			$page = 'post-type-archive-' . sanitize_html_class( $post_type );
		} elseif ( is_author() ) {
			$author    = $wp_query->get_queried_object();
			$page = 'author-' . $author->user_nicename;
		} elseif ( is_category() ) {
			$cat       = $wp_query->get_queried_object();
			$page = 'category-' .$cat->slug;
		} elseif ( is_tag() ) {
			$tag  = $wp_query->get_queried_object();
			$page = 'tag-'. $tag->slug;
			
		} elseif ( is_tax() ) {
			$term = $wp_query->get_queried_object();
			$page = 'tax-'.$term->slug;
			
		}
	}
	return $page;
}