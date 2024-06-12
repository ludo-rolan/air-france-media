<?php 
class Search{
    public static  function register()
    {
        add_action('wp_ajax_posts_count_search', [self::class, 'posts_count_search']);
        add_action('wp_ajax_nopriv_posts_count_search', [self::class, 'posts_count_search']);
    }

    function get_found_posts_count($post_types, $city_name) {
        $query = array(
            'post_type' => $post_types,
            'post_status' => 'publish',
            'fields' => 'ids',
            's' => $city_name,
            'posts_per_page'=>'-1'

        );
        $results = new WP_Query($query);
        return $results->found_posts;
    }

    function posts_count_search(){
        global $site_config;
        $post_types=$site_config['search_wanted_post_types'];
        $cities = json_decode(stripslashes($_POST['cities']));
        $keyword = esc_attr($_POST['keyword']);
        $counted_cities = [];
        $results_word = __('Résultat', AFMM_TERMS);
        foreach ($cities as $key => $city) {
            if(strpos($city->city_name, '/')){
                $combined_cities = explode('/', $city->city_name);
                foreach($combined_cities as $single_city) {
                    if (stripos($single_city, $keyword) !== false) {
                        $result_count = self::get_found_posts_count($post_types, $single_city);
                        $results_word_suffix = $result_count !== 1 ? 's' : '';
                        $counted_cities[] = [$single_city, $result_count, $results_word.$results_word_suffix];
                    }
                }
            }
            else {
                $result_count = self::get_found_posts_count($post_types, $city->city_name);
                $results_word_suffix = $result_count !== 1 ? 's' : '';
                $counted_cities[] = [$city->city_name, $result_count, $results_word.$results_word_suffix];
            }
        }
        echo json_encode($counted_cities);
        wp_reset_postdata();
        die;
    }
}
Search::register();