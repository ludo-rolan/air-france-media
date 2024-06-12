<?php
const PROJECT_NAME = 'aftg';
require '../init.php';
require '../helper.php';
require '../aftg_helper.php';


$countries=array('fr'=>
['allemagne','espagne','hongrie','italie','norvege','pologne','republique-tcheque','suede','suisse'],'en'=>
['germany','spain','hungary','italy','norway','poland','czech-rep','sweden','switzerland']);

foreach($countries as $key=>$lg_pays){
    global $sitepress;
    $sitepress->switch_lang($key);
    println_flush("-------------") ;
    println_flush("LANGUAGE : ".$key) ;

    foreach($lg_pays as $pays_slug ){
        $pays = get_term_by('slug', $pays_slug, 'destinations');
        println_flush("country :".$pays_slug.' : '.$pays->term_id) ;
        $cities=AFTG_Helper::get_dastination_by_country($pays_slug);
        foreach($cities as $city){
            println_flush('--'.$city->post_title.' : '.$city->ID);
            $time=get_post_meta($city->ID,'time',true);
            println_flush('----'.$time['time_timeZone']);
            $time['time_timeZone']="GMT+02:00";
            update_post_meta($city->ID,"time", $time);
            $time=get_post_meta($city->ID,'time',true);
            println_flush('----'.$time['time_timeZone']);
          

        }
    }
}
