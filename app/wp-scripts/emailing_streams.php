<?php
// issue appears only in this file
const PROJECT_NAME = 'afmm';
require 'init.php';
require 'helper.php';

// YOU CANNOT FIRE SCRIPT FROM BROWSER XD
if($isRunningFromBrowser = !isset($GLOBALS['argv'])){
    wp_redirect(home_url());
}else{
    get_option('enableflux') != '' ? ManageEmails::emailing_streams() : update_option('fluxlogs', "logs sont désactivés");
}