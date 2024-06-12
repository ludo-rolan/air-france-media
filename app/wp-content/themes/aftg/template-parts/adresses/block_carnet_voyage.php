<?php
global $site_config;
$pertinence_param = $_GET['par'] ?? '';
$selected = ($pertinence_param == 'pertinence') ? 'selected' : '';
?>
<?php
        $blocks = [
            [
                'id' => 'carnet_voyage_block',
                'img' => $site_config['tg_carnet_voyage']['trash'],
                'first_text' => 'Effacer tout mon Carnet  ?',
                'style' => 'width: 335px; position: relative;relative; border: 1px solid #000000; margin-right: 30px; margin-left: 30px; ',
                'span_style' => 'color:#000000;text-transform: uppercase;width:100%;text-align:left',
            ],
        ];
        get_block_filters($blocks);
?>
<div class="container">
    <div class="row">

        <h2 class="adresse_title"><?php _e('Mes adresses enregistrées', AFMM_TERMS) ?></h2>

        <div class="bloc-tri ml-auto mr-3">
            <label for="tri"><?php _e('Trier par',AFMM_TERMS)?>:</label>
            <select class="bloc-tri-select" name="tri-adresse" id="tri-adresse">
                <option value="date_ajout"><?php echo __('Date d\'ajout', AFMM_TERMS) ?></option>
                <option <?php echo $selected ?> value="pertinence"><?php echo __('Pertinence', AFMM_TERMS) ?></option>
            </select>
        </div>
    </div>
    <div class="carnet_container">
    </div>
</div>