<?php
const PROJECT_NAME = 'aftg';
require 'init.php';
require 'helper.php';
 $vimeo_file = DATA_DIR . 'vimeo/Videos-destinations-TG-habillage-EN-VOLS-16022022.csv';

 $array = [];
 if (($open = fopen($vimeo_file, "r")) !== FALSE) {
     $count = 0;
     while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
         if ($count != 0) {
             $array[$data['1']] =  $data['2'];
             println_flush($data['1']."->".  $data['2']);
         }
         $count++;
     }
     fclose($open);
 }
 
    $args = array(
        'numberposts' => -1,
        'post_type' => 'destination',
        'field'=>'ids', 
    );
    $posts = get_posts( $args );
    $meta_value = array();
    $counter=0;
    foreach ($posts as $post) {
        $code_iata = get_post_meta( $post->ID, 'origin_code',true );
        if(!empty($code_iata)){  
            $main_video=get_post_meta($post->ID, "mainVideo",true);
            println_flush("-----------------------------");
            if(isset($array[$code_iata])){

                println_flush($code_iata." start with post ID".$post->ID);
                isset($main_video['mainVideo_videoPlayerId'])? println_flush(" OLD ID :".$main_video['mainVideo_videoPlayerId']):"";
                $main_video['mainVideo_videoPlayerId']=$array[$code_iata];
                update_post_meta($post->ID, "mainVideo",$main_video);
                println_flush(" It has been updated with the VIMEO ID :".$array[$code_iata]);
                $counter++;
            }
            else {
                println_flush($code_iata." It has not been updated , no VIMEO FOUND ");
            }

        }
        
    }
    println_flush("number of posts updated ".$counter);


