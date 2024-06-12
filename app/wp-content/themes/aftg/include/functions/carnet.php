<?php 
class Carnet{
    public static  function register()
    {
        add_action('wp_ajax_carnet_voyage', [self::class, 'carnet_voyage']);
        add_action('wp_ajax_nopriv_carnet_voyage', [self::class, 'carnet_voyage']);
    }

    public static function carnet_voyage() {
        $adresses_ids_param=$_POST['ids'];
        $adresses_ids = explode(',', $adresses_ids_param);
    
        $adresses = get_posts(
            array(
                'post_type' => 'adresse',
                'posts_per_page' => 9,
                'orderby' => 'publish_date',
                'order' => 'DESC',
                'post__in' => $adresses_ids
            )
        ); 
        include (locate_template('/template-parts/adresses/registred_adresses.php')); 
        die;

    }

}
Carnet::register();