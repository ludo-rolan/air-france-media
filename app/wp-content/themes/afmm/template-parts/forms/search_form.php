<div data-backdrop="false" class="modal  rechercher " id="MainSearch" tabindex="-1" role="dialog" aria-labelledby="MainSearch">
    <div class="modal-dialog modal-lg" style="height: 100%; margin:0" role="document">
        <div class="modal-content" style="height:100%">
            <div class="modal-body" id="modal-body-search">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>

                <div class="rechercher-container">
                    <div  id="navbarMobileSearch">
                       
                            <input autocomplete="off" class="rechercher-input" type="text" name="s"  placeholder="<?php _e('Votre recherche ici',AFMM_TERMS) ?>">
                            <button class="rechercher-input-btn" >
                                <i data-action="search"  class="fa fa-search"></i>
                            </button>
                       
                    </div>
                    <div class="rechercher-pop" style="display:none">
                        <div class="rechercher-pop-title">
                            <span><?php echo __('Valider le choix proposé :', AFMM_TERMS) ?></span>
                        </div>
                        <div class="rechercher-pop-results"  data-url="<?php echo get_site_url()?>">
                            <span ></span>
                            <span></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>