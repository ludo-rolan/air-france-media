<?php

$wp_host = $_SERVER['HTTP_HOST'];
$wp_db = '';

$project_name = defined('PROJECT_NAME') ? PROJECT_NAME : 'wp';

$afmm_host = 'afmm.rw.loc';
$aftg_host = 'aftg.rw.loc';

$upload_dir = "wp-content/uploads";
if($project_name=='afmm' || $wp_host==$afmm_host ) {
	$wp_db = "afmm";
	// define project name
	if (!defined('PROJECT_NAME')){
		define('PROJECT_NAME', 'afmm');
	}
	// define upload dir
	$uploads_dir = 'wp-content/uploads/afmm';

}
elseif( $project_name=='aftg' || $wp_host==$aftg_host ) {
	$wp_db = "aftg";
	if (!defined('PROJECT_NAME')){
		define('PROJECT_NAME', 'aftg');
	}
	$uploads_dir = 'wp-content/uploads/aftg';
}

define( 'DB_NAME', $wp_db );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'password' );
define( 'DB_HOST', 'mysql' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );
define( 'SAVEQUERIES', isset($_GET['debug_queries']) );
define( 'IS_PREPROD', true );
define( 'IS_PROD', false );

define('WP_HOME','http://'.$wp_host.'/');
define('WP_SITEURL','http://'.$wp_host.'/');
define('UPLOADS', $uploads_dir);

define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );


$table_prefix = 'wp_';
// phpinfo();

define( 'WP_DEBUG', true );


if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);
