<?php
if(isset($ids)){
    $pageFlotteAvions = new WP_Query(array('post_type' => 'avion', 'post__in'=>explode(',',$ids) ));
}else{
    $pageFlotteAvions = new WP_Query(array('post_type' => 'avion',  'posts_per_page'   => -1 ));
}

$count=0;
    ?>
    <div class="row">
        <div class="avion_container col-lg-8">
        <?php
        while($pageFlotteAvions->have_posts()) { 
            $pageFlotteAvions->the_post();
            $avionMetaData = get_post_meta(get_the_ID());
            if (!empty($avionMetaData['donnees_avion'])){
                $donneesAvion=maybe_unserialize($avionMetaData['donnees_avion'][0]);
                $image_url = '';
                $image_ids =  $donneesAvion['donnees_avion_avion_gallery'];
            ?>
            
            <div class="avion_second_title"><?php echo $donneesAvion['donnees_avion_titre_secondaire']; ?></div>
            
            <div class="avion">
                <div class="avion_slider">
                    <div class="avion_slider_cercle-left owl-prev<?php echo $count; ?>"><img class="avion_slider_cercle_arrow" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/arrow-left.png'; ?>" /></div> 
                    <div class="owl-carousel avion_carousel">
                    <?php
                            if ( ! empty( $image_ids ) ) :
                                $image_ids = explode( ',', $image_ids);
                                foreach($image_ids as $image_id) {
                                    $image_url = wp_get_attachment_url($image_id); ?>
                                    <div class="item">
                                        <img src="<?php echo $image_url; ?>" />
                                    </div>
                                    <?php
                                }
                            endif;
                        ?>
                    </div>
                    <div class="avion_slider_cercle-right owl-next<?php echo $count; ?>"><img class="avion_slider_cercle_arrow" src="<?php echo STYLESHEET_DIR_URI . '/assets/img/arrow-right.png'; ?>" /></div> 
                </div>
                <div class="avion_infos">
                    <div class="avion_trait_parent">
                        <div class="avion_trait">
                        </div>
                    </div>
                    <div class="avion_infos_wrapper">
                        <div class="avion-title">
                            <a href="<?php the_permalink(); ?>"><span class="avion-num"><?php echo $donneesAvion['donnees_avion_vol_num']; ?></span> <?php the_title(); ?></a>
                        </div>
                        <div class="row avion_data">
                            <div class="col-md-6 avion_data_bloc">
                                <ul class="avion-bullets">
                                    <li><span class="avion_metas_name"><?php _e("Sièges :",AFMM_TERMS) ?> </span><?php echo $donneesAvion['donnees_avion_siege']; ?></li>
                                    <li><span class="avion_metas_name"><?php _e("Vitesse :",AFMM_TERMS) ?> </span><?php echo $donneesAvion['donnees_avion_vitesse']; ?></li>
                                    <li><span class="avion_metas_name"><?php _e("Envergure :",AFMM_TERMS) ?> </span><?php echo $donneesAvion['donnees_avion_envergure']; ?></li>
                                
                                </ul>
                                
                            </div>
                            <div class="col-md-6 avion_data_bloc">
                                <ul class="avion-bullets">
                                    <li><span class="avion_metas_name"><?php _e("Longueur : ",AFMM_TERMS) ?></span><?php echo $donneesAvion['donnees_avion_longueur']; ?></li>
                                    <li><span class="avion_metas_name"><?php _e("Type Moteur :",AFMM_TERMS) ?> </span><?php echo  $donneesAvion['donnees_avion_typeMoteur']; ?></li>
                                </ul>
                            </div>
                        
                        </div>
                        <div class="row avion_btn_wrapper">
                            <div class="col-md-6">
                                <button type="button" class="btn avion_btn-cabine" id="cabine-plan-<?php echo $count;?>" data-toggle="modal" data-target=".bd-example-modal-lg"><?php _e('Voir La Cabine', AFMM_TERMS); ?></button>
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header avion_modal_header">
                                                <button type="button" class="close avion_close"
                                                        data-dismiss="modal">
                                                    ×
                                                </button>
                                            </div>
                                            <?php
                                                $cabine_id = $donneesAvion['donnees_avion_cabine_image'];
                                                $cabine_src = wp_get_attachment_url($cabine_id);
                                            ?>
                                            <img id="popup-img" class="w-100" src="<?php echo $cabine_src; ?>" alt="cabine-avion">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a class="btn avion_btn-vol" href="<?php echo $donneesAvion['donnees_avion_Lien_recher_un_vol']; ?>"><?php _e('Rechercher Un Vol', AFMM_TERMS); ?></a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <?php
            $count++;
            }    
        }
        ?>
        </div>
        <div class="col-lg-4 d-flex justify-content-center">
           <div class="avion_pub"></div>
        </div>
    </div>
    