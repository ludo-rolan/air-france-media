<div class="contact-infos">
    <?php $data_doc = ICL_LANGUAGE_CODE == 'fr' ? wp_get_upload_dir()['url'].'/POLITIQUE-DE-CONFIDENTIALITE-FR-1.pdf' : wp_get_upload_dir()['url'].'/POLITIQUE-DE-CONFIDENTIALITE-EN.pdf' ?>
    <p><?php  _e("Les informations recueillies à partir de ce formulaire sont enregistrées et transmises au webmaster chargé du traitement de votre message. ", AFMM_TERMS)?></p>

    <p><?php  _e("Conformément à la loi « Informatique et Libertés » du 6 janvier 1978 modifiée, vous disposez d’un droit d'accès, de rectification et de suppression des données vous concernant. Vous pouvez également, pour des motifs légitimes, vous opposer au traitement des données vous concernant. Pour exercer vos droits, vous pouvez contacter notre Délégué à la protection des données, la société Magellan Consulting à l’adresse suivante : dpd@reworldmedia.com, en justifiant de votre identité.", AFMM_TERMS)?></p>

    <p><?php  _e("En savoir plus sur", AFMM_TERMS)?><a href="<?php echo $data_doc?>" target="_blank"><?php _e(" la gestion de vos données et vos droits.", AFMM_TERMS)?></a></p>
</div>