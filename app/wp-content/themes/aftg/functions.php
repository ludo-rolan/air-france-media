<?php

require(STYLESHEET_DIR . '/include/site-config.php');
require(STYLESHEET_DIR . '/include/functions/omeps.php');

require(STYLESHEET_DIR . '/include/tax/tax.php');
require(STYLESHEET_DIR . '/include/cpt/cpt.php');
require(STYLESHEET_DIR . '/include/option/options.php');

require (STYLESHEET_DIR.'/include/hooks.php');
require (STYLESHEET_DIR . '/include/functions/shortcodes.php');
require (STYLESHEET_DIR.'/include/multi_select_filters.php');

require(STYLESHEET_DIR . '/include/bloc_breadcrumb.php');
require (STYLESHEET_DIR.'/include/functions/ranking.php');

require(STYLESHEET_DIR . '/include/destinations_block_filters.php');
require(STYLESHEET_DIR . '/include/functions/dynamic_filters_content.php');

require_once(STYLESHEET_DIR."/partners.php");
require(STYLESHEET_DIR . '/include/functions/destinationsResult.php');

require(STYLESHEET_DIR . '/include/functions/carnet.php');

if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'destination'){
    require(STYLESHEET_DIR . '/include/functions/destination_filtre_post_type.php'); 
}
