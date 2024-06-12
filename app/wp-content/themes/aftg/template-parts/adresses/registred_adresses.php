<div class="row">
        <?php foreach ($adresses as $adresse_post) :
            $title2_destination = $adresse_post->post_title;
            $link_destination = get_permalink($adresse_post->ID);
            $id_adresse = $adresse_post->ID;
            $image_html = get_the_post_thumbnail($id_adresse, 'large', ["class" => "post_image"]);
            $title_destination = get_the_terms($id_adresse, "filtre")[0]->name;
        ?>
            <div class="col-md-4 post" >
            <a  data-id="<?php echo $id_adresse; ?>"><div class="post_card_badge post_card_badge_check"> </div></a>
                <?php include(locate_template('/template-parts/country/preview_destination_post.php')) ?>
            </div>
        <?php endforeach ?>
</div>