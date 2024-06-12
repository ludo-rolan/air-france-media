<?php
$good_to_know_meta= json_decode(get_post_meta(get_the_ID(),'goodToKnow_content', true),true);
$post_metas = get_post_meta(get_the_ID());

$city_timezone = str_replace('GMT', '', unserialize($post_metas['time'][0])['time_timeZone']);
$timezone_gmt = $city_timezone;
$sign = strpos($city_timezone, '+') === false ? '-' : '+';
$city_timezone = str_replace($sign, '', $city_timezone);
$horaire_administration= $good_to_know_meta['administration'];
$horaire_banque= $good_to_know_meta['banks'];
$decalage=$good_to_know_meta['daylightSavingTime'];
$electricite=$good_to_know_meta['voltage'];
$indicatif= $good_to_know_meta['localIndicative'];
$plugTypes = $good_to_know_meta['plugTypes'];

function convert_to_utf8 ($undeco){
    $str     = str_replace('<br />n','<br/>',$undeco);
    $str_replaced = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $str);
    return $str_replaced;
}
?>
<div class="bon_a_savoir" id="goodToKnow">
<h2 class="infosPratiques_title"><?php _e('Bon à savoir',  AFMM_TERMS); ?></h2>
<div class="row savoir-row">
                <div class="savoir-container">
                <div class="savoir-single-main-container-results" >
                    <div class ="img-container"> <img href= ''src="<?php echo STYLESHEET_DIR_URI . '/assets/img/phone.png' ?>"></img></div>
                    
                    <div class="destination-single-container-savoir-plus">
                        <p class="savoir-single-title"><?php   _e("INDICATIF",  AFMM_TERMS)  ?></p>
                        <span class="savoir-single-content"> <?php  echo $indicatif['number']?><span> <?php _e(" + numéro du correspondant",  AFMM_TERMS)?></span></span>
                    </div>
                </div>
                <div class="savoir-single-main-container-results" >
                    <div class ="savoir-single-curtime"><?php echo $sign.$city_timezone; ?> </div>
                    <div class="destination-single-container-savoir-plus">
                        <p class="savoir-single-title"><?php   _e("Heure Locale",  AFMM_TERMS)  ?></p>
                        <span class="savoir-single-content-heurelocale"> <span> <?php echo 'GMT  '.$timezone_gmt.'</br>'.convert_to_utf8($decalage);?></span></span>
                    </div>
                </div>
                <div class="savoir-single-main-container-results" >
                    <div class ="img-container"> <img href= ''src="<?php echo STYLESHEET_DIR_URI . '/assets/img/building.png' ?>"></img></div>
                    <div class="destination-single-container-savoir-plus">
                        <p class="savoir-single-title"><?php   _e("Horaires d’ouverture",  AFMM_TERMS)  ?></p>
                        <span class="savoir-single-content-horrairesouvertures"> <span class="savoir-single-content_span" > <?php  echo $horaire_banque['title']?> : <?php echo convert_to_utf8($horaire_banque['openingHours']);?></span><span class="savoir-single-content_span"> <?php  echo $horaire_administration['title']?> : <?php echo convert_to_utf8($horaire_administration['openingHours']); ?></span></span>
                    </div>
                </div>
                <div class="savoir-single-main-container-results" >
                    <!-- here we go -->
                    <div class ="img-container">
                        <?php foreach($plugTypes as $plugType){ ?>
                            <img href= ''src="<?php echo STYLESHEET_DIR_URI . '/assets/img/plugTypes/'.$plugType.'.svg' ?>" width="60px"></img>
                        <?php } ?>
                    </div>
                    <div class="destination-single-container-savoir-plus">
                        <p class="savoir-single-title"><?php   _e("Électricité",  AFMM_TERMS)  ?></p>
                        <span class="savoir-single-content"> <span> <?php  echo $electricite ?></span></span>
                    </div>
                </div>
                </div>
        </div>
        <div class="savoir-tips">
            <?php
                echo convert_to_utf8($good_to_know_meta['tips']); 
            ?>
        </div>
</div>