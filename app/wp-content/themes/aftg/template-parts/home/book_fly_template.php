<?php
global $post;
global $site_config;
$post_type = $post->post_type;
$depart='PAR';
if(is_single()){
	if($post_type == 'destination')
	{
		$destination = get_post_meta((int) $post->ID,'origin_code',true);
	}
	if($post_type == 'adresse' || $post_type == 'post')
	{
		$destination_taxonomy = get_the_terms( $post_id, 'destinations');
		$args = [
			"post_type" => 'destination',
			'tax_query' => array(
				array (
					'taxonomy' => 'destinations',
					'field' => 'term_id',
					'terms' => $destination_taxonomy[0]->term_id,
				)
			)
		];
		$cache_key = $site_config['aftg_cache']['book_fly_template']['key'];
		$cache_time = $site_config['aftg_cache']['book_fly_template']['time'];
		$destination_query = get_data_from_cache($cache_key, 'book_fly_template', $cache_time, function () use ($args) {
			return new WP_Query($args);
		});
		$destination = get_post_meta((int) $destination_query->post->ID, 'origin_code', true);
	}
	$url_redirection = generate_vol_url($depart, $destination).'&'.generate_utm_params('Button','RDC');
}
else{
	$url_redirection ="https://wwws.airfrance.fr/exchange/deeplink?language=" . ICL_LANGUAGE_CODE . "&country=fr&target=/travel-guide/where-can-i-fly-to&".generate_utm_params('Button','RDH');
}
?>
<div class="align-items-center appoint-fly-content" >
<div class="container">
<a id="rdh_guide_pastille"  href="<?php echo $url_redirection ?>" target="_blank">
	<div class="row appoint-fly">
		<img src="<?php echo $site_config['tg_hp_destination']['plane'] ?>" />
		<span><?php _e('réserver un vol', AFMM_TERMS); ?></span>
	</div>
</a>	
</div>
</div>