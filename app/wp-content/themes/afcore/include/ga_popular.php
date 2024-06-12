<?php
class Ga_popular
{
    static private $ga_analytics = 'https://www.googleapis.com/analytics/v3/data/ga';
    private $token = "ya29.A0ARrdaM-1RLBvbNR-X-EiIPVz3dqOMb_o9lWYME3ns50AWkwySJUvGeygBH8spa48DcKJ5UHf8wvXquVMRkCemb1byVsTCk0G11nmE6nhGp5fbaShtuN2_3PaAnwlh_Mu3SaLbxTxgfkgYaFVxEUidqYay5C9";
    private $slugs;

    function __construct($website_id, $start_date, $end_date, $metrics, $dimensions, $sort, $segment, $max_results, $filters)
    {
        $this->slugs = self::connect_to_api_get_popular_slugs($website_id, $start_date, $end_date, $metrics, $dimensions, $sort, $segment, $max_results, $filters);
    }

    function connect_to_api_get_popular_slugs($website_id, $start_date, $end_date, $metrics, $dimensions, $sort, $segment, $max_results, $filters)
    {
        $url_args = [
            'access_token'=> $this->token,
            'ids'=> $website_id,
            'dimensions'=>$dimensions,
            'metrics'=>$metrics,
            'sort'=>$sort,
            'segment'=>$segment,
            'start-date'=>$start_date,
            'end-date'=>$end_date,
            'filters'=>$filters,
            'max-results'=>$max_results,
        ];

        $query_url = add_query_arg($url_args,self::$ga_analytics);
        $resp = self::after_access_refresh($query_url);
        if ($resp[0] != 200) {
            $this->token = self::refresh_token();
            $url_args['access_token'] = $this->token;
            $query_url = add_query_arg($url_args,self::$ga_analytics);
            $resp = self::after_access_refresh($query_url);
        }
        return $resp[1];
    }

    function after_access_refresh($query_url)
    {
        $resp = wp_remote_get($query_url);
        // if we can't get data from GA, display the most popular statically.
        if (!is_wp_error($resp) && $resp['response']['code'] == 200) {
            return [$resp['response']['code'], json_decode($resp['body'])->rows];
        }else{
            return[0,null];
        }
    }

    function handle_post_by_slug($slugs,$post_type)
    {
        // the query to return POSTs
        $args = [
            'post_name__in' => $slugs,
            'post_type' => $post_type,
            'orderby' => 'post_name__in',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'suppress_filters' => 0,
        ];
        return get_posts($args);
    }

    function filtre_slug_from_path($url)
    {
        // iso data : get only valide slugs
        $url_parts = explode('/', $url);
        array_pop($url_parts);
        return end($url_parts);
    }

    function retrieve_data($post_type)
    {
        // here we return posts
        $slugs_iso = [];
        if($this->slugs){
            foreach ($this->slugs as $slug) {
                $slugs_iso[] = self::filtre_slug_from_path($slug[0]);
            }
        }
        // if current page is popular ,remove it from most read section
        $current_slug = get_queried_object()->post_name;
        $pos = array_search($current_slug,$slugs_iso);
        if($pos!==false){unset($slugs_iso[$pos]);}
        $slugs = array_unique($slugs_iso);
        return self::handle_post_by_slug($slugs,$post_type);
    }

    function refresh_token()
    {
        // this part need a config from google api
        // for enabling refresh token
        // the main token has 3600s then it expires

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.google.com/o/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=496821731505-f59qsak8devk01vthfoci7t63u29bg2g.apps.googleusercontent.com&client_secret=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&refresh_token=1%2F%2F0dmWQTQhHcMqwCgYIARAAGA0SNwF-L9IrFq1XPAMDsB6vC70IcxLWgxeCZgh1-cls09Pq1H0bZRNWoXbpfjlzsc7e99mHqM-MnTg&grant_type=refresh_token',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: __Host-GAPS=1:Ut8Ig8AZgt5CmkmKxILId1BA08pN_Q:tLEkoxfTwM1rECe2'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $token_ref = json_decode($response);
        return $token_ref->access_token;
    }
}
