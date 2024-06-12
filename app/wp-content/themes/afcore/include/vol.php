<?php

class Airport
{
    private static $_instance;
    private  $version = "1.0";
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        if(isset($_GET['install_airport'])){
            add_action('init', [$this, 'install_airport']);
        }
    }
  
    public function install_airport()
    {
        $actual_version = get_option('airport_module_version', 0);
        if ($actual_version != $this->version) {
            echo "Nouvelle version 'airport' : {$this->version} </br>";
            update_option('airport_module_version', $this->version);
            $this->create_table_airport();
        }
    }
    public function create_table_airport()
    {
        global $wpdb;
        echo "Début création de table {$wpdb->prefix}airport </br>";
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}airport (
					id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					code_iata varchar(5) NOT NULL UNIQUE,
                    full_name varchar(200) NOT NULL,
                    city_code_iata varchar(5) NOT NULL,
                    city_name varchar(200) NOT NULL,
                    country_code_iata varchar(5) NOT NULL
				);";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        echo "Fin création de table {$wpdb->prefix}airport </br>";
    }


    public static function save_airport($code_iata, $city_code_iata, $full_name, $country_code_iata, $city_name)

    {
        $args = array(
            "code_iata" => $code_iata,
            "full_name" => $full_name,
            "city_name" => $city_name,
            "city_code_iata" => $city_code_iata,
            "country_code_iata" => $country_code_iata
        );

        global $wpdb;
        $table_name = $wpdb->prefix . "airport";
        $results = $wpdb->get_col(
            $wpdb->prepare("SELECT id  FROM {$table_name} WHERE code_iata=%s", $code_iata)
        );
        if (count($results)) {
            if ($wpdb->update($table_name, $args, array('id' => $results[0]))) {
                return $results[0];
            }
        } else {
            if ($wpdb->insert($table_name, $args)) {
                return $wpdb->insert_id;
            }
            else {
                echo "Insertion d'aéroport échouée, code : {$code_iata} </br>";
            }
        }

        return false;
    }

}
Airport::get_instance();


class Vol
{
    private static $_instance;
    private  $version = "1.0";
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        if(isset($_GET['install_vol'])){
            add_action('init', [$this, 'install_vol']);
        }
    }
  
    public function install_vol()
    {
        $actual_version = get_option('vol_module_version', 0);
        if ($actual_version != $this->version) {
            echo "Nouvelle version 'vol' : {$this->version} </br>";
            update_option('vol_module_version', $this->version);
            $this->create_table_vol();
        }
    }
    public function create_table_vol()
    {
        global $wpdb;
        echo "Début création de table {$wpdb->prefix}vol </br>";
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vol (
					id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					origin_id bigint(20) NOT NULL,
					destination_id bigint(20) NOT NULL,
                    UNIQUE (origin_id,destination_id),
                    CONSTRAINT `fk_origin`
                        FOREIGN KEY (origin_id) REFERENCES {$wpdb->prefix}airport(id),
                    CONSTRAINT `fk_destination`
                        FOREIGN KEY (destination_id) REFERENCES {$wpdb->prefix}airport(id)
                    
				);";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        echo "Fin création de table {$wpdb->prefix}vol </br>";
    }


    public static function save_vol($origin_id, $destination_id)

    {
        $args = array(
            "origin_id" => $origin_id,
            "destination_id" => $destination_id
        );

        global $wpdb;
        $table_name = $wpdb->prefix . "vol";
        $results = $wpdb->get_col(
            $wpdb->prepare("SELECT id  FROM {$table_name} WHERE origin_id=%d and destination_id=%d", $origin_id, $destination_id)
        );
        if (!count($results)) {
            if ($wpdb->insert($table_name, $args)) {
                return $wpdb->insert_id;
            }
            else {
                echo "Insertion de vol échouée, ID origine : {$origin_id} , ID destination : {$destination_id} </br>";
            }
        }

        return false;
    }

}
Vol::get_instance();
