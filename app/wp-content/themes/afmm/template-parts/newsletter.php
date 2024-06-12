<div class="newsletter">
    <img class="newsletter_image" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/envolope.png' ?>" alt="Afmm">
    <div id="nf-form-1-cont" class="nf-form-cont" aria-live="polite" aria-labelledby="nf-form-title-1" aria-describedby="nf-form-errors-1" role="form">
        <span id="nf-form-title-1" class="nf-form-title">
            <h3><?php _e("évadez-vous avec",AFMM_TERMS) ?> </br> <?php _e("notre newsletter",AFMM_TERMS) ?></h3>
        </span>
        <div class="nf-form-wrap ninja-forms-form-wrap">
            <div class="nf-response-msg"></div>
            <div class="nf-debug-msg"></div>
            <div class="nf-before-form">
                <nf-section>

                </nf-section>
            </div>
            <div class="nf-form-layout">
                <form name="newsletter_form">
                    <?php wp_nonce_field("newsletter_form",'csrf_token'); ?>
                    <div>
                        <div class="nf-before-form-content">
                            <nf-section>
                                <div class="nf-form-fields-required"><?php _e('Les champs marqués d’un astérisque <span class="ninja-forms-req-symbol">*</span> sont obligatoires.',AFMM_TERMS) ?></div>

                            </nf-section>
                        </div>
                        <div class="nf-form-content ">
                            <nf-fields-wrap>
                                <nf-field>
                                    <div id="nf-field-2-container" class="nf-field-container email-container  label-above ">
                                        <div class="nf-before-field">
                                            <nf-section>

                                            </nf-section>
                                        </div>
                                        <div class="nf-field">
                                            <div id="nf-field-2-wrap" class="field-wrap email-wrap" data-field-id="2">



                                                <div><label for="email_nl" id="nf-label-field-2"> <?php _e('ADRESSE MAIL ',AFMM_TERMS) ?></label></div>


                                                <div class="nf-field-element">
                                                    <input type="email" value="" class="ninja-forms-field nf-element" id="email_nl" name="email" autocomplete="email" placeholder="<?php _e('VOTRE ADRESSE MAIL',AFMM_TERMS) ?>" aria-invalid="false" aria-describedby="nf-error-2" aria-labelledby="nf-label-field-2" required="">
                                                </div>
                                                <div class="nf-field-element nf-field-element-checkbox">
                                                    <input type="checkbox" value="" class="ninja-forms-field nf-element nf-field-element-checkbox-custom" id="checkbox_nl" name="checkbox_nl" value="newsletter">
                                                    <span class="checkmark"></span>
                                                    <label for="checkbox_nl" class="nf-field-element-checkbox-label"><?php echo _e("Recevez chaque semaine la newsletter EnVols", AFMM_TERMS); ?></label>
                                                </div>
                                                <div class="nf-field-label"></div>
                                                <div class="nf-field-element submit-container">
                                                    <input id="submit_nl" class="ninja-forms-field nf-element " type="button" value="<?php _e('je m\'inscris', AFMM_TERMS) ?>">
                                                </div>
                                                <div class="nf-error-wrap"></div>



                                            </div>
                                        </div>
                                        <div class="nf-after-field">
                                            <nf-section>

                                                <div class="nf-input-limit"></div>

                                                <div id="nf-error-2" class="nf-error-wrap nf-error" role="alert"></div>


                                            </nf-section>
                                        </div>
                                    </div>
                                </nf-field>
                                <nf-field>
                                    <div id="nf-field-4-container" class="nf-field-container submit-container  label-above  textbox-container">
                                        <div class="nf-before-field">
                                            <nf-section>

                                            </nf-section>
                                        </div>
                                        <div class="nf-after-field">
                                            <nf-section>

                                                <div class="nf-input-limit"></div>

                                                <div id="nf-error-4" class="nf-error-wrap nf-error" role="alert"></div>


                                            </nf-section>
                                        </div>
                                    </div>
                                </nf-field>
                            </nf-fields-wrap>
                        </div>
                    </div>
                </form>
            </div>
            <div class="nf-after-form">
                <nf-section>

                </nf-section>
            </div>
        </div>
    </div>
</div>