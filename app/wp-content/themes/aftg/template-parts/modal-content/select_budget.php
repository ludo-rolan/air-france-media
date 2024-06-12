<?php
$family_svg   = AF_THEME_DIR_URI . '/assets/img/af-biblio/BUDGET/family.svg';
$currency_svg = AF_THEME_DIR_URI . '/assets/img/af-biblio/BUDGET/currency.svg';
$maxPrice     = 2000;
?>

<input type="hidden" id="passenger_num" value="0"/>
<input type="hidden" id="price_num" value="0"/>

<div class="container">
    <div class="multi_select_filter_title">
        <h2><?php _e( 'Sélectionnez le nombre de voyageur(s) et votre budget*' , AFMM_TERMS) ?></h2>
    </div>
    <div class="select_budget">
        <div class="select_budget select_budget_container flex-column">
            <div class="select_budget_img" style="background-image: url('<?php echo $family_svg; ?>');"></div>
            <div class="select_budget select_budget_control justify-content-between">
                <a class="decrement_num" href="javascript:void(0)">-</a>
                <div class="passenger_content">
                    <span class="passenger_count">1</span>
                    <span><?php _e( 'voyageur', AFMM_TERMS ) ?></span>
                </div>
                <a class="increment_num" href="javascript:void(0)">+</a>
            </div>
        </div>
        <div class="select_budget select_budget_container select_budget_container--second flex-column">
            <div class="select_budget_img select_budget_img" style="background-image: url('<?php echo $currency_svg; ?>');"></div>
            <div class="select_budget select_budget_control justify-content-center">
                <div class="select_budget_range select_budget_range-slider">
                    <span class="select_budget_range_value_1"></span>
                    <input class="select_budget_slide_1" step="10" value="0" min="0" max="<?php echo $maxPrice ?>" type="range">
                    <input class="select_budget_slide_2" step="10" value="<?php echo $maxPrice ?>" min="0" max="<?php echo $maxPrice ?>" type="range">
                    <span class="select_budget_range_value_2"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-4">
    <span>
        <a class="multi_select_filter-btn mt-3"><?php _e( 'Valider', AFMM_TERMS) ?></a>
    </span>
</div>
<div class="d-flex justify-content-center align-items-center" style="margin-top: 10rem">
    <span class="select_budget_notice_sentence"><?php _e('* Concerne uniquement les tarifs des billets d’avion', AFMM_TERMS) ?></span>
</div>