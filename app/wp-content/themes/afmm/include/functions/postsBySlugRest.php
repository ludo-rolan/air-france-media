<?php
class postsBySlugRest
{
    public static function register()
    {
        add_action('rest_api_init', [self::class, 'getPosts']);
    }

    function getPosts()
    {
        register_rest_route('afmm', '/posts', [
            "methods" => 'GET',
            "callback" => 'postsBySlugRest::getData'
        ]);
    }

    function getData(WP_REST_Request $request)
    {
        global $sitepress;
        global $site_config;
        $current_language = apply_filters('wpml_current_language', NULL);
        $sitepress->switch_lang($current_language);

        $slug = $request->get_param('slug');

        $cache_key = $site_config['afmm_cache']['maillageAjax']['key'] . $current_language . '_' . $slug;
        $cache_time = $site_config['afmm_cache']['maillageAjax']['time'];

        $all_data = get_data_from_cache($cache_key, 'maillage', $cache_time, function () use ($slug) {
            $data = [];

            $posts = get_posts(array(
                'post_type' => 'post',
                'numberposts' => 3,
                'suppress_filters' => false,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'destinations',
                        'field' => 'slug',
                        'terms' => $slug,
                        'include_children' => false
                    )
                )
            ));
            foreach ($posts as $post) {
                $cat = get_the_category($post->ID)[0];
                $data[] = [
                    'img' => get_the_post_thumbnail_url($post, 'medium'),
                    'title' => $post->post_title,
                    'cat' => $cat->name,
                    'link_post' => get_permalink($post),
                    'link_cat' => get_category_link($cat),
                ];
            }
            return $data;
        });

        return new WP_REST_Response(['data' => $all_data]);
    }
}
postsBySlugRest::register();
