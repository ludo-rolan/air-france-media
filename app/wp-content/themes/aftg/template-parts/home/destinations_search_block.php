<?php
    global $sitepress;
    $lang =  apply_filters('wpml_current_language', NULL) !== 'fr' ? '/en/' : '/';
    $destinations_url = $lang . 'listing-des-destinations/';
?>
<section class="dest-search img-responsive">
	<div class="container">
		<div class="d-flex flex-column align-items-center">
			<h3 class="dest-search-title text-white text-uppercase mt-5 mb-3"><?php _e('Vous connaissez déjà votre destination ?', AFMM_TERMS) ?></h3>
			<div class="dest-search-input mt-3">
				<i class="fa fa-search" aria-hidden="true"></i>
				<input id="search_input" class="ui-autocomplete-input" autocomplete="off" type="text" name="s" placeholder="<?php _e('Votre destination : Espagne, New York...', AFMM_TERMS) ?>">
                <div class="dest-search-suggestions">
                    <li class="suggestions-opening"><?php _e('Validez le choix proposé : ', AFMM_TERMS) ?></li>
                    <div class="dest-search-suggestions_ul_container"></div>
                    <li class="suggestions-opening"><?php _e('En voir plus ? ', AFMM_TERMS) ?>
                        <a href="<?php echo $destinations_url; ?>"><?php _e('Consultez la liste des destinations', AFMM_TERMS) ?></a>
                    </li>
                </div>
                <i class="fa fa-bars" aria-hidden="true"></i>
			</div>
			<div class="dest-search-loader"><img src="/wp-content/themes/aftg/assets/img/loader2.gif" />
			<?php _e("Veuillez patienter, nous recherchons la destination de vos rêves...",AFMM_TERMS) ?> </div>
		</div>
		<div class="dest-search-breaker">
			<hr>
			<span class="text-uppercase text-white"><?php _e('Ou', AFMM_TERMS) ?></span>
			<hr>
		</div>
		<div class="d-flex flex-column align-items-center">
			<h3 class="dest-search-title text-white text-uppercase mt-5 mb-3"><?php _e('Découvrez votre destination idéale...', AFMM_TERMS) ?></h3>
			<span class="dest-search-title-sub text-white"><?php _e('Affinez votre séléction en choisissant un ou plusieurs filtres', AFMM_TERMS) ?></span>
		</div>
		<?php
		$blocks = [
			[
				'id' => 'hp_destination_block',
				'img' => $site_config['tg_hp_destination']['settings'],
				'first_text' => 'Quoi ?',
				'second_text' => 'Arts et culture...',
                'style' => 'width: 200px; position: relative;',
			],
			[
				'id' => 'hp_destination_block',
				'img' => $site_config['tg_hp_destination']['earth'],
				'first_text' => 'Où ?',
				'second_text' => 'Asie et Moyen...',
				'style' => 'width: 200px; position: relative;',
			],
			[
				'id' => 'hp_destination_block',
				'img' => $site_config['tg_hp_destination']['calender'],
				'first_text' => 'Quand ?',
				'style' => 'width: 200px; position: relative;',
			],
			[
				'id' => 'hp_destination_block',
				'img' => $site_config['tg_hp_destination']['currency'],
				'first_text' => 'Combien ?',
				'style' => 'width: 200px; position: relative;',
			],
		];
		get_block_filters($blocks);
        include(locate_template('template-parts/forms/destinations_search_form.php')); ?>
	</div>
    <div class="d-flex col-xl-12 justify-content-center">
        <a id="result-button-tg" class="af-btn dest-search-result"><?php _e('SÉLECTIONNER UN FILTRE', AFMM_TERMS) ?></a>
    </div>
</section>