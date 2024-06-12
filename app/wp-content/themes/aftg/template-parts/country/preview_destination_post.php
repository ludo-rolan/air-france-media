<a class="post_link" href="<?php echo $link_destination ?>" <?php echo($preview_type == 'adresse'? 'target="_blank"':"")?>)>
    <?php
    echo $image_html;
    ?>
</a>
<div class="post_info">
    <?php if(isset($destination_partenaire) && $destination_partenaire!=''){ ?>
    <div class="post_card_badge_wide">
        <span><?php echo strtoupper($destination_partenaire)?></span>
    </div>
    <?php } ?>
    <h3 class="post_cat"><?php echo  $title_destination ?></h3>
    <?php if($destination_vol_url && $destination_code !='' && $preview_type == 'destination' && $price != '-'){ ?>
            <p class="destination_subtitle"><a id='rdh_guide_vignette'  href="<?php echo $destination_vol_url ?>"> <?php echo $title2_destination ?> </a></p>
    <?php }elseif($preview_type == 'destination' && $price !=false ){ ?>
        <p class="destination_subtitle"><?php echo $title2_destination ?></p>
    <?php }elseif($preview_type != 'destination' && isset($link_destination)){ ?>
        <p class="destination_subtitle"><a  <?php echo($preview_type == 'adresse'? 'class="preview_adresse"':"")?> href="javascript:void(0);" data-link="<?php echo $link_destination ?>"><?php echo $title2_destination ?></a></p>
    <?php } ?>
</div>