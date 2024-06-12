<?php
global $post;
$dest_metas = get_post_meta(get_the_ID());
$data_array = json_decode($dest_metas['monthInformation_content'][0]);

?>

<table class="table meteo_table">
    <thead>
        <tr>
            <th scope="col"><?php _e('mois', AFMM_TERMS);?></th>
            <th scope="col"><?php _e('Température moyenne Min (°C)', AFMM_TERMS);?></th>
            <th scope="col"><?php _e('Température moyenne Max (°C)', AFMM_TERMS);?></th>
            <th scope="col"><?php _e('Précipitations moyennes (MM)', AFMM_TERMS);?></th>
            <th scope="col"><?php _e('Les meilleures périodes pour partir', AFMM_TERMS);?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($data_array)){
            foreach($data_array as $data){
               ?>
                <tr>
                    <th><?php echo (isset($data->month)) ? _e($data->month,  AFMM_TERMS) : ''; ?></th>
                    <td><?php echo (isset($data->minTempCelsius)) ? $data->minTempCelsius : ''; ?></td>
                    <td><?php echo (isset($data->maxTempCelsius)) ? $data->maxTempCelsius : ''; ?></td>
                    <td><?php echo (isset($data->rainMm)) ? $data->rainMm : ''; ?></td>
                    <td><?php if(isset($data->bestMonth)){
                        echo ($data->bestMonth) ? "<img src= '".STYLESHEET_DIR_URI.'/assets/img/green_check_icon.svg'."'/>" : ""; 
                    } ?>
                    </td>
                </tr>
                
               <?php

            }
        }
        
        ?>

    </tbody>
</table>
