<?php 
    global $site_config;
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_register_style( 'jquery-ui-min', AF_THEME_DIR_URI.'/assets/stylesheets/lib/jquery-ui.css',array(),CACHE_VERSION_CDN,false);
    wp_enqueue_style( 'jquery-ui-min' );  
    PROJECT_NAME == 'afmm' ? $clicke_from = 'media' : $clicke_from = 'guide';
    $localisation_lang = apply_filters('get_user_country_code',NULL);
?>
<img class="inspirations-avion"  src="<?php echo $site_config['inspirations_icon']['avion-black']; ?>" alt="embarquement_immédiat">

<h3 class="inspirations-form-title"><?php echo __('EMBARQUEMENT IMMÉDIAT', AFMM_TERMS) ?></h3>

<form id="flightBooking" name="flightBooking">
    <?php wp_nonce_field("flightBooking",'csrf_token'); ?>
    <input type="hidden" name="data-localisation" value="<?php echo $localisation_lang ?>">
    <div class="row">
        <div class="col">
            <label class="inspirations-label"><?php echo __('DÉPART DE', AFMM_TERMS) ?></label>
            <div class="inspirations-input-container">
                <img src="<?php echo $site_config['inspirations_icon']['avion-on'] ?>" alt="avion_on">
                <input name="departing-from" class="inspirations-input" autocomplete="off" placeholder="<?php _e('Origine',AFMM_TERMS) ?>" />            
                <div class="inspirations-input-datalist inspirations-input-datalist-from" id="departing-from"></div>
            </div>

        </div>
        
    </div>
    <div class="row inspirations-input-warning inspirations-input-warning-from">
        <div class="col"><?php  _e("Veuillez séléctionner une ville de départ.", AFMM_TERMS) ?></div>
    </div>
    <div class="row">
        <div class="col">
            <label class="inspirations-label"><?php echo __('ARRIVÉE À', AFMM_TERMS) ?></label>
            <div class="inspirations-input-container">
                <img src="<?php echo $site_config['inspirations_icon']['avion-off'] ?>" alt="avion_off">
                <input name="arriving-at" class="inspirations-input" autocomplete="off" placeholder="<?php _e('Destination',AFMM_TERMS) ?>" />            
                <div class="inspirations-input-datalist" id="arriving-at"></div>
            </div>
        </div>

    </div>
    <div class="row inspirations-input-warning inspirations-input-warning-at">
    <div class="col"><?php  _e("Veuillez séléctionner une ville d'arrivée.", AFMM_TERMS) ?></div>
    </div>

    <div class="row">
        <div class="col">
            <label class="inspirations-label"><?php echo __('VOYAGE', AFMM_TERMS) ?></label>
            <select class="custom-select custom-select-sm inspirations-select inspirations-input-container" id="trip-kind" name="trip-kind">
                <option value="aller-retour"><?php echo __('Aller-Retour', AFMM_TERMS) ?></option>
                <option value="aller"><?php echo __('Aller simple', AFMM_TERMS) ?></option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="inspirations-label"><?php echo __('DATES', AFMM_TERMS) ?></label>
            <div class="inspirations-input-container-date inspirations-input-container">
                <img src="<?php echo $site_config['inspirations_icon']['calender'] ?>" id="datepicker-aller-calendar" alt="calendar">
                <input type="text" class="inspirations-input inspirations-datepicker" id="datepicker-aller" name="aller-date" placeholder="<?php _e('Aller',AFMM_TERMS) ?>" autocomplete="off"> </input>
                <img src="<?php echo $site_config['inspirations_icon']['calender'] ?>" id="datepicker-retour-calendar" alt="calendar">
                <input type="text" class="inspirations-input inspirations-datepicker" id="datepicker-retour" name="retour-date" placeholder="<?php _e('Retour',AFMM_TERMS) ?>" autocomplete="off"> </input>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label class="inspirations-label"><?php echo __('PASSAGERS', AFMM_TERMS) ?></label>

            <select class="custom-select custom-select-sm inspirations-select" name="passengers">
                <option value="1"><?php echo '1 '. __('passager', AFMM_TERMS); ?></option>
                <?php 
                for($i=2; $i<=9; $i++) {
                ?>
                    <option value="<?php echo $i; ?>"><?php echo $i . __(' passagers', AFMM_TERMS); ?></option>
                <?php 
                }
                ?>
            </select>
        </div>
        <div class="col">
            <label class="inspirations-label"><?php echo __('CABINE', AFMM_TERMS) ?></label>

            <select class="custom-select custom-select-sm inspirations-select" name="cabin">
                <option value="ECONOMY"><?php echo __('Economy', AFMM_TERMS) ?></option>
                <option value="PREMIUM"><?php echo __('Premium Economy', AFMM_TERMS) ?></option>
                <option value="BUSINESS"><?php echo __('Business', AFMM_TERMS) ?></option>
                <option value="FIRST"><?php echo __('La Première', AFMM_TERMS) ?></option>
            </select>
        </div>
    </div>
    <button id="<?php echo 'bkt_'.$clicke_from.'_sidebar' ?>"  class="inspirations-btn btn"><?php echo __('réservez votre vol', AFMM_TERMS) ?></button>
</form>