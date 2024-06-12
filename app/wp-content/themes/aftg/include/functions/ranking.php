<?php


class Ranking
{
    private static $_instance;
    private static $table = 'ranking';
    private  $version = "1.0";
    public static function get_instance()
    {
        add_action('save_post',  [self::class, 'save_destination_envies_mois'], 10, 3);
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        if(isset($_GET['install_ranking'])){
            add_action('init', [$this, 'install_ranking']);
        }
    }
  
    public function install_ranking()
    {
        $actual_version = get_option('ranking_module_version', 0);
        if ($actual_version != $this->version) {
            update_option('ranking_module_version', $this->version);
            $this->create_table_ranking();
        }
    }
    public function create_table_ranking()
    {
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}ranking (
					id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					destination_id bigint(20) NOT NULL,
					taxonomie_id bigint(20) NOT NULL,
					ranking int(20) NOT NULL,
                    taxonomie_slug varchar(200)	,
					type_taxonomie varchar(200)	
				);";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    public static function insert_ranking($destination_id, $taxonomie_id, $ranking, $type_taxonomie, $taxonomie_slug)

    {
        $args = array(
            "destination_id" => $destination_id,
            "taxonomie_id" => $taxonomie_id,
            "ranking" => $ranking,
            "type_taxonomie" => $type_taxonomie,
            "taxonomie_slug" => $taxonomie_slug
        );

        global $wpdb;
        $table_name = $wpdb->prefix . "ranking";
        $results = $wpdb->get_col(
            $wpdb->prepare("SELECT id  FROM {$table_name} WHERE destination_id=%d and taxonomie_id=%d", $destination_id, $taxonomie_id)
        );
        if (count($results)) {
            if ($wpdb->update($table_name, $args, array('id' => $results[0]))) {
                return $results[0];
            }
        } else {
            if ($wpdb->insert($table_name, $args)) {
                return $wpdb->insert_id;
            }
        }

        return false;
    }

    public static function save_destination_envies_mois( $post_id, $post, $update ) {
        if (
            'destination' == $post->post_type
            &&
            'publish' == $post->post_status
            
        ) {
            $taxs = ['envies', 'mois'];
             foreach ($taxs as $tax) {
            $taxs_to_be_linked=[];
            $taxs_to_be_detached=[];
            $terms = get_terms([
                'taxonomy' => $tax,
                'hide_empty' => false
                ]);
            
            foreach($terms as $term){
                $key=$tax.'_'.$term->term_id;
                if(isset($_POST[$key]) && $_POST[$key]>0){
                    $taxs_to_be_linked[]=$term->term_id;
                    self::insert_ranking($post_id, $term->term_id, $_POST[$key],$tax, $term->slug);
                }
                else {
                    $taxs_to_be_detached[]=$term->term_id;
                    self::insert_ranking($post_id, $term->term_id, 0,$tax, $term->slug);
                }
            }	
            wp_remove_object_terms( $post_id,$taxs_to_be_detached , $tax);
            wp_set_object_terms( $post_id, $taxs_to_be_linked, $tax  );
    
            }
        }
    }
}
Ranking::get_instance();
