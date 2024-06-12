<?php 
global $site_config;
$pays_langue_info = $site_config['afmm_user_location'];
$location_country = "";
$local_langues =array();
if(function_exists('icl_object_id')){
	if(ICL_LANGUAGE_CODE =='fr'){
		$location_country = $pays_langue_info['Libellé Pays FR'];
		$langues = isset($pays_langue_info['Code Langue']) ? $pays_langue_info['Code Langue'] : array();

		foreach($langues as $langue){
			$local_langues[] = mb_strtoupper($langue['Libellé Langue FR']); 
		}
	}elseif(ICL_LANGUAGE_CODE =='en'){
		$location_country = $pays_langue_info['Libellé Pays EN'];
		$langues = isset($pays_langue_info['Code Langue']) ? $pays_langue_info['Code Langue'] : array();
		
		foreach($langues as $langue){
			$local_langues[] = mb_strtoupper($langue['Libellé Langue EN']); 
		}
	}
}
 $dic_pays_langue = Localisation_Geoip::$pays_langue;
 $langues =array();
 $pays =array();
foreach($dic_pays_langue as $pays_lang){
	$pays[] = mb_strtoupper($pays_lang['Libellé Pays FR']);
	$langues_data = $pays_lang['Code Langue'];

	foreach($langues_data as $langue){
		if(!in_array(mb_strtoupper($langue['Libellé Langue FR']),$langues)){
			array_push($langues,mb_strtoupper($langue['Libellé Langue FR'])); 
		}
	}
}

?>
<div class='nl-page-title'>
	<h2 class="text-center nl-page-name"><?php _e("Inscription newsletter",AFMM_TERMS) ?></h2>
</div>
<div class="nl-body afmm">
	<div id="nl_body">
		<form action="" method="post" name="newsletterpage" class="form-horizontal" id="signupForm_nl">
			<?php wp_nonce_field("newsletterpage",'csrf_token'); ?>
			<div class="offers">
				<div class="offers_item">
					<input type="checkbox" value="pms_optin_edito"  id="choice-1" class="offer_checkbox" name="OPTIN_CHECKBOX" required />
					<label for="choice-1"></label>
                    <div class="choice-1-error"></div>
					<span class="check-box"></span>
					<strong><?php echo __('Envie d’évasion ? Recevez chaque semaine la newsletter ', AFMM_TERMS);?>
                        <a href="<?php echo home_url() ?>">
                            <img class="af-nl-logo-centered" src="<?php echo $site_config['logo_nl'] ?>" alt="logo_website">
                         </a>
                        <?php  echo __(' et trouvez l’inspiration', AFMM_TERMS); ?> </br> <?php echo __('grâce à notre sélection d’adresses, d’événements et de destinations de voyages', AFMM_TERMS); ?>
                    </strong>
				</div>

				<div class="offers_item">
					<input type="checkbox" value="pms_optin_part" id="choice-3" class="offer_checkbox" name="PART_CHECKBOX" />
					<label for="choice-3"></label>
					<span class="check-box"></span>
					<strong><?php echo __('Envie de voyager ? Abonnez-vous à la newsletter d’Air France pour recevoir', AFMM_TERMS);?></br> <?php echo __('des offres personnalisées et des informations sur nos nouveaux services ', AFMM_TERMS) ?></strong>
				</div>

			</div>
			<div class="row sign-form">
				<div class="col-lg-12 col-md-8 offset-lg-3">
					<div class="row">
					<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="pays_nl"><?php _e("PAYS",AFMM_TERMS) ?></label>
								<select type="text" required name="PAYS" class="form-control" id="country_nl">
								<option  value="<?php echo $location_country ? $location_country : ''; ?>"  selected <?php echo empty($location_country) ? 'hidden disabled' : ''; ?>> <?php echo $location_country ? $location_country : 'PAYS'; ?> </option>
								<?php 
								sort($pays);
								if(!empty($location_country)){
									if (($key = array_search($location_country, $pays)) !== false) {
										unset($pays[$key]);
									}
								}
								
								foreach($pays as $pay): ?>	
									<option value="<?php echo $pay ?>"> <?php echo $pay ?> </option>
								<?php endforeach ?>
								</select>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="langue_nl"><?php _e("LANGUE",AFMM_TERMS) ?></label>
								<select type="text" required name="LANGUE" class="form-control" id="langue_nl">
								<option value="<?php echo !empty($local_langues) && isset($local_langues[0]) ? $local_langues[0] : ''; ?>"  selected <?php echo empty($local_langues) ? 'disabled hidden' : ''; ?>> <?php echo !empty($local_langues) && isset($local_langues[0]) ? $local_langues[0] : 'LANGUE'; ?> </option>
								<?php 
								sort($langues);
								if(!empty($local_langues) ){
									foreach($local_langues as $lang){
										if (($key = array_search($lang, $langues)) !== false) {
											unset($langues[$key]);
										}
									}
									$langues = array_merge($local_langues,$langues);
									
									if (($key = array_search($local_langues[0], $langues)) !== false) {
										unset($langues[$key]);
									}
								}
								
								foreach($langues as $langue): ?>	
									<option value="<?php echo $langue ?>"> <?php echo $langue ?> </option>
								<?php endforeach ?>
								</select>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="codepostal_nl"><?php _e("Code postal",AFMM_TERMS) ?></label>
								<input type="text" class="form-control" placeholder="<?php _e('CODE POSTAL',AFMM_TERMS) ?>" id="codepostal_nl" name="CODEPOSTAL"/>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="birthday-date_nl"><?php _e("date de naissance",AFMM_TERMS) ?></label>
								<input type="text" class="form-control" placeholder="<?php _e('JJ/MM/AAAA',AFMM_TERMS) ?>" id="birthday-date_nl" name="BDATE" data-inputmask-mask="99/99/9999"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label for="email_nl"><?php _e("ADRESSE MAIL",AFMM_TERMS) ?></label>
								<input type="text" required name="MAIL" checked="checked" <?php echo (isset($_GET["email_newsletter"]) && $_GET["email_newsletter"]) ? "value=".$_GET["email_newsletter"] : ''; ?> class="form-control" placeholder="<?php _e('VOTRE ADRESSE MAIL',AFMM_TERMS) ?>" id="email_nl"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<button type="submit" class="btn btn-default btn-block"><?php _e("Je m'inscris",AFMM_TERMS) ?></button>
						</div>
					</div>
				</div>
			</div>
			<!-- <label tabindex="0" class="radio" for="193_1">Oui</label> -->
		</form>
	</div>
	<div id="confirmation-old" style="display: none">
		<h2 class="text-center page-title"><?php _e("Merci !",AFMM_TERMS) ?></h2>
		<h3 class="text-center page-sub-title">
			<?php _e("Nous avons bien pris en compte votre demande pour l'adresse MAIL_NL",AFMM_TERMS) ?>
			<br/><br>
			<?php _e("Vous êtes déjà inscrit à notre newsletters",AFMM_TERMS) ?>
			<br/><br>
			<?php _e("à bientôt sur ",AFMM_TERMS) ?>
			<a href="<?php echo home_url() ?>">
				<img class="af-nl-logo-centered" src="<?php echo $site_config['logo_nl'] ?>" alt="logo_website">
			</a>
		</h3>
	</div>

	<div id="confirmation-new" style="display: none">
		<h2 class="text-center page-title"><?php _e("Merci !",AFMM_TERMS) ?></h2>
		<h3 class="text-center page-sub-title"><?php _e("Nous avons bien pris en compte votre demande pour l'adresse MAIL_NL",AFMM_TERMS) ?><br>
		<?php _e("Vous venez de recevoir un <strong>mail de confirmation d'inscription</strong> (n'oubliez pas de regarder dans vos courriers indésirables).",AFMM_TERMS) ?><br>
			<br>
			<?php _e("à bientot sur",AFMM_TERMS) ?> 
			<a href="<?php echo home_url() ?>">
				<img class="af-nl-logo-centered" src="<?php echo $site_config['logo_nl'] ?>" alt="logo_website">
			</a>
		</h3>
	</div>
</div>