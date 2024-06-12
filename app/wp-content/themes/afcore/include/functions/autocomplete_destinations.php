<?php 
class AutocompleteDestinations{
    public static  function register()
    {
        add_action('wp_ajax_autocomplete_destinations', [self::class, 'autocomplete_destinations']);
        add_action('wp_ajax_nopriv_autocomplete_destinations', [self::class, 'autocomplete_destinations']);
        add_action('rest_api_init', [self::class, 'afmm_embarquement']);
    }
    
    static function afmm_embarquement()
	{   
        register_rest_route('afmm', "/embarquement", array(
            'methods' => 'GET',
            'callback' => 'AutocompleteDestinations::get_afmm_airports',

        ));
	}

    static function get_afmm_airports(WP_REST_Request $request )
	{
        echo self::autocomplete_airports($request->get_params());
	}
    
    function autocomplete_airports($post_body) {
        global $wpdb;
        $table_airport = $wpdb->prefix . "airport";
        $table_vol = $wpdb->prefix . "vol";
        $query = '';
        $origin_id = '';
        if($post_body['origin_id']) {
            $origin_id = esc_attr($post_body['origin_id']);
            $query = "SELECT SQL_CACHE a.id, a.code_iata, a.full_name  FROM {$table_airport} a WHERE a.id IN (SELECT v.destination_id FROM {$table_vol} v WHERE v.origin_id = {$origin_id})";
        }
        else {
            $query = "SELECT SQL_CACHE id, code_iata, full_name  FROM {$table_airport};";
        }
        global $site_config;
        $cache_key = $site_config['afcore_cache']['autocomplete_airports']['key'].'_'.$origin_id;
        $cache_time = $site_config['afcore_cache']['autocomplete_airports']['time'];
        echo get_data_from_cache($cache_key, 'autocomplete_destinations', $cache_time, function () use ($query, $wpdb) {
            $results = $wpdb->get_results(
                $wpdb->prepare($query),
                ARRAY_A
            );
            return json_encode($results);
        });
        die;

    }

    function autocomplete_destinations() {
        if (!wp_verify_nonce( $_POST['nonce'], 'autocomplete-destinations' )) {
            header('HTTP/1.0 403 Forbidden');
            die('You are not allowed to access.');
        }
        global $wpdb;
        $table_airport = $wpdb->prefix . "airport";
        $keyword = esc_attr($_POST['keyword']);
        global $site_config;
        $cache_key = $site_config['afcore_cache']['autocomplete_destinations']['key'].'_'.$keyword;;
        $cache_time = $site_config['afcore_cache']['autocomplete_destinations']['time'];
        echo get_data_from_cache($cache_key, 'autocomplete_destinations', $cache_time, function () use ($table_airport, $keyword, $wpdb) {
            $results = $wpdb->get_results(
                $wpdb->prepare("SELECT DISTINCT city_name FROM {$table_airport} WHERE city_name LIKE \"{$keyword}%\";"),
                ARRAY_A
            );
            return json_encode($results);
        });
        die;

    }
    
    
}
AutocompleteDestinations::register();