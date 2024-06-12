<?php
// j'ai ajouté global site config psk on l'utilise dans la template
global $site_config;
$adresses = get_destination_adresse($post_term_parent->term_id, 7);
$term = get_the_terms($post_id, 'destinations')[0];
$term_param = '&destinations_id='.$term->term_id;
$filtres = get_terms([
        'taxonomy'=>'filtre',
        'parent'=>0,
        'orderby' => 'meta_value',
        'meta_key' => 'filter_order_number'
]);
$count = 0;
?>
<?php if($adresses){ ?>
    <div class="bloc" id="adresses">
        <h2 class="destination_title"><?php _e('nos adresses à  ', AFMM_TERMS);
                                        echo $post_title; ?></h2>
        <?php include(locate_template('template-parts/components/subnav.php')) ?>

        <div class="row">
            <?php for ($i = 0; $i < 4; $i++) {
                if (isset($adresses[$i])) {
                    $id_destination = $adresses[$i]->ID;
                    $destination_meta =  get_post_meta($adresses[$i]->ID);

                    $link_destination = get_permalink($id_destination);
                    $topics = get_the_terms($adresses[$i]->ID,'post_tag');
                    $title_destination = join(' - ', wp_list_pluck($topics, 'name'));
                    $destination_partenaire = $destination_meta['partenaire_partenaire'][0] ?? '';
                    $image_dimensions =  apply_filters('select_cropped_img', ['custom_size' => 'thumb-w-141-h-101', 'original_size' => 'medium']);
                    $image_html = get_the_post_thumbnail($id_destination, $image_dimensions, ["class" => "post_image"]);
                    $title2_destination = $adresses[$i]->post_title;
                    $preview_type = 'adresse';
            ?>
                    <?php if ($i == 0 || $i == 2) { ?>
                        <div class="inspirations-col">
                        <?php } ?>
                        <div class="col post">
                            <a class="carnet_badge post_card_badge" data-id="<?php echo $id_destination; ?>"></a>
                            <?php include(locate_template('template-parts/country/preview_destination_post.php')); ?>
                        </div>
                        <?php if ($i == 1 || $i == 3) {
                            echo "</div>";
                        } ?>
                <?php }
            } ?>
                <div class="inspirations-col">
                    <div class="  inspirations-form">
                        <?php include(locate_template("template-parts/forms/embarquement_form.php")); ?>
                    </div>
                </div>
                        </div>
                        <div class="row">
                            <?php for ($i = 4; $i < 7; $i++) { ?>
                                <?php
                                if(isset($adresses[$i])){
                                    $id_destination = $adresses[$i]->ID;
                                    $destination_meta =  get_post_meta($adresses[$i]->ID);

                                    $link_destination = get_permalink($id_destination);
                                    $topics = get_the_terms($adresses[$i]->ID,'post_tag');
                                    $title_destination = join(' - ', wp_list_pluck($topics, 'name'));
                                    $destination_partenaire = $destination_meta['partenaire_partenaire'][0]??'';
	                                $image_size = apply_filters('select_cropped_img', ['custom_size' => 'thumb-w-141-h-101', 'original_size' => 'medium']);
                                    $image_html = get_the_post_thumbnail($id_destination, $image_size, ["class" => "post_image"]);
                                    $title2_destination = $adresses[$i]->post_title;
                                    $preview_type = 'adresse';
                                ?>
                                    <div class="col-md-4 post">
                                        <a class="carnet_badge post_card_badge" data-id="<?php echo $id_destination; ?>"></a>
                                        <?php include(locate_template('/template-parts/country/preview_destination_post.php')); ?>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                        <div class="blue_button-big">
                            <?php if($site_config["langue_selectionner"] == 'en'){
                                $href = '/en/adresse/?' . $term_param . '&af_filtres=tout-voir' ;
                            }
                            else{
                                $href = '/adresse/?' . $term_param . '&af_filtres=tout-voir' ;
                            }
                            ?>

                            <a href="<?php echo $href ?>" target="_blank"><?php echo _e("VOIR TOUTES NOS ADRESSES", AFMM_TERMS)?></a>
                        </div>
        </div>
    <?php } ?>