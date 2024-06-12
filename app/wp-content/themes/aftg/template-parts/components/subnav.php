<?php if(function_exists('icl_object_id')){
    $current_language = ICL_LANGUAGE_CODE;
}
 ?>
<div class="subnav">
    <ul>
        <li>
            <?php 
            if($current_language == 'en'){
                $href_all = '/en/adresse/?' . $term_param .'&af_filtres=tout-voir';
            }else{
                $href_all = '/adresse/?' . $term_param .'&af_filtres=tout-voir';
            }
             ?>
            <a href="<?php echo $href_all ?>" id="subnav-default-active"><span><?php _e("tout voir",AFMM_TERMS) ?></span></a>
        </li>
        <?php foreach ($filtres as $filtre) : ?>
            <li>
                <?php 
                if($current_language == 'en'){
                    $url_adresse = '/en/adresse/?';
                }else{
                    $url_adresse = '/adresse/?';
                }
                echo  '<a href="'. $url_adresse . $term_param . '&af_filtres=' . $filtre->slug . '" target="_blank"><span>' . $filtre->name . '</span></a>' ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>