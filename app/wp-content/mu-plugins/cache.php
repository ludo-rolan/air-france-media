<?php

define('DEFAULT_CACHE_VERSION_CDN' , 1 );
if(isset($_GET['newcdn'])){
	add_action('wp', function(){
		$cdn =	get_cache_version_cdn() ;
		$cdn ++ ;
		if (is_multisite()) update_site_option('cache_version_cdn', $cdn) ;
		else update_option('cache_version_cdn', $cdn) ;
	});
}
define('CACHE_VERSION_CDN' , get_cache_version_cdn());
function get_cache_version_cdn(){
  if (is_multisite()){
    $cache_version_cdn = get_site_option('cache_version_cdn', DEFAULT_CACHE_VERSION_CDN) ;
  }
  else {
  	$cache_version_cdn = get_option('cache_version_cdn', DEFAULT_CACHE_VERSION_CDN) ;
  }
  return ($cache_version_cdn >  DEFAULT_CACHE_VERSION_CDN)? $cache_version_cdn : DEFAULT_CACHE_VERSION_CDN ;
}

defined('USE_DETECT_MOBILE') or define('USE_DETECT_MOBILE' , true);
if( isset($_SERVER['IS_MOBILE']) ) {
    if ( $_SERVER['IS_MOBILE']=='1' || isset($_GET['mobile']) )
    	define('MOBILE_MODE' , true);
    
} else {
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if( isset($_GET['mobile']) || 
    	preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)) ) 
        define('MOBILE_MODE' , true);
}
function rw_is_mobile(){
	return defined('MOBILE_MODE') && MOBILE_MODE;
}

function rw_is_tablet(){
  return defined('DEVICE_TYPE') && DEVICE_TYPE == 'Tablet';
}
function purge_nginx_cache($url){
  // to purge the nginx cache of a url "https://host/uri", we need to get request to "https://host/purge/uri" 
  $parse_url = parse_url($url) ;
  $home_url = $parse_url['scheme'] .'://' .$parse_url['host'].'/' ;

  if(defined('PURGE_BY_HTTP') and PURGE_BY_HTTP){
    $http_home_url = str_replace('https://','http://', $home_url);
    $url_to_purge = str_replace($home_url, $http_home_url . 'spurge/', $url ) ;
  }else{
    $url_to_purge = str_replace($home_url, $home_url . 'purge/', $url ) ;
  }

  wp_remote_get($url_to_purge, array(
    'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36'
  ));
  $ch = curl_init($url_to_purge);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Mobile Safari/537.36');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if(defined('IS_PREPROD') && IS_PREPROD){
    curl_setopt($ch, CURLOPT_USERPWD, 'reworldmedia:reworldmedia');
  }
  $data = curl_exec($ch);
  curl_close($ch);
  // return true if the response is 200 OK
  return strpos($data, '200 OK') !== false;
}

function purge_home_nginx_cache(){
  $home_url = get_home_url();
  purge_nginx_cache($home_url."/");
}
  

function purge_nginx_cache_for_post_and_categories_and_hp($post_id){

  // get permalink of the post and its categories and parent categories and purge the nginx cache for each of them
  $permalink = get_permalink($post_id);
  $categories = get_the_category($post_id);

  $categories_ids = array_map(function($category){
    return $category->term_id;
  }, $categories);

  // get all ancestors of the categories
  $categories_ancestors = array_map(function($category_id){
    return get_ancestors($category_id, 'category');
  }, $categories_ids); 
  $categories_ancestors = array_unique(array_merge(...$categories_ancestors));
  $categories_ids = array_merge($categories_ids, $categories_ancestors);
  $categories = array_map(function($category_id){
    return get_term_link($category_id);
  }, $categories_ids);
  $categories = array_merge($categories, [$permalink]);
  $categories = array_unique($categories);
  
  $categories = array_map(function($category){
    return purge_nginx_cache($category);
  }, $categories);
  $categories && purge_home_nginx_cache();
}