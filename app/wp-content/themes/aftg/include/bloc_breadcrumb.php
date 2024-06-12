<?php 

function get_breadcrumb($cheaper_price = false)
{
    $delimiter = ' &rsaquo; ';
    $home = __('Accueil', AFMM_TERMS);
    global $site_config;
    global $post;
    $lang= ICL_LANGUAGE_CODE=="en" ? "en" :"";
    $homeLink = $site_config['site_url']."/".$lang;


    echo '<div class="crumbs';
    if(is_page() || is_post_type_archive('destination')) echo ' crumbs-separated ';
    echo '"><div class="crumbs-elements"><a href="' . $homeLink . '">' . $home . '</a>';

    if(is_singular( 'destination' ) || is_tax('destinations') || is_singular('adresse')) { 
        if(has_term( '', 'destinations' )) {
            echo $delimiter . '<a href="' . $homeLink . '">TRAVEL GUIDE</a>' . $delimiter . '<a>DESTINATION</a>' ;
            $ville=get_the_terms(the_post(), 'destinations')[0];
            $destination = $ville;
            $destination_tree = array();
            while($destination->parent != 0) {
                $destination = get_term($destination->parent, 'destinations');
	            $destination_tree[] = $destination;
            }
            $last_elem_in_breadcrumb = end($destination_tree);
            foreach (array_reverse($destination_tree) as $destination) {
                if ($last_elem_in_breadcrumb == $destination){
                    echo  $delimiter . '<a>' . $destination->name . '</a>';
                }
            }
        }
        if(!is_singular('destination') && !is_singular('adresse')){
            echo '<div class="page-name">' . $destination->name . '</div>';
        }
        elseif(isset($_GET['info_pratiques'])) {
            echo $delimiter . '<a href="' . get_term_link($destination->term_id, 'destinations') . '">' . $destination->name . '</a>';
        }elseif(isset($destination) && isset($ville)){
            echo $delimiter . '<a href="' . get_term_link($destination->term_id, 'destinations') . '">' . $destination->name . '</a>';
            if(is_singular('adresse')){
                $link = $homeLink ."/destination/".$ville->slug;
                echo $delimiter . '<a href="' . $link . '">' . $ville->name . '</a>';
            };
            echo '<h1 class="page-name'.(is_singular('adresse')?"-adresse" : "").'">' . get_the_title() . '</h1>';
        }else{
        	echo $delimiter . 'Aucune destination choisi ';
        }
        if(!is_singular('adresse') && !isset($_GET['info_pratiques'])) {
            if (is_tax('destinations') && $cheaper_price !=false){
                $post_meta = get_post_meta($cheaper_price[0]['post_id']);
            }else{
                $post_meta = get_post_meta(get_the_ID());
            }
            $price = $post_meta['lowestPrice'][0] ;
            if(!empty($price)) {
                $origin_code = $post_meta['origin_code'][0];
                $destination_vol = generate_vol_url('PAR',$origin_code) . '&' . generate_utm_params('Header','RDC');
                $title2_destination = __("Vol à partir de ",AFMM_TERMS)  . $price . "&euro; " . __("A/R*",AFMM_TERMS);
                echo '<div class="destination_page-subtitle"><a id="rdh_guide_title" href="'.$destination_vol.'">'; echo $title2_destination . '</a></div>';
            }
        } 
    }
    elseif(is_page() || is_single() || is_post_type_archive('destination')) {
        echo $delimiter . '<a href="' . $homeLink . '">TRAVEL GUIDE</a>';
        if(!is_post_type_archive('destination')) {
            echo '<div class="page-name">' . get_the_title() . '</div>';
        }
    }
    elseif (is_home() || is_front_page()) {
        echo $delimiter . '<div class="page-name">TRAVEL GUIDE</div>';
    }
    elseif(is_tax('aux_alentours')){
        $alentour_term_id = get_queried_object()->term_id;
        $cache_key = $site_config['aftg_cache']['breadcrumb_alentours_bloc']['key'].'_'.$alentour_term_id;
        $cache_time = $site_config['aftg_cache']['breadcrumb_alentours_bloc']['time'];
        $alentour_destination = get_data_from_cache($cache_key,'breadcrumb_alentours_bloc',$cache_time,function() use ($alentour_term_id){
            return get_posts(
                [
                    'post_type' => 'destination',
                    'numberposts' => 1,
                    'tax_query' => [
                        [
                            'taxonomy' => 'aux_alentours',
                            'field' => 'term_id',
                            'terms' => [$alentour_term_id],
                            'operator' => 'IN',
                        ]
                    ]
                ]
            );
        });
        echo $delimiter . '<a href="' . $homeLink . '">TRAVEL GUIDE</a>' . $delimiter . '<a>DESTINATION</a>' ;
        $destination_ville = get_the_terms($alentour_destination[0],'destinations')[0];
        $destinations = array();
        while($destination_ville->parent != 0) {
            $destination_ville = get_term($destination_ville->parent, 'destinations');
            array_push($destinations, $destination_ville);
        }
        foreach (array_reverse($destinations) as $destination) {
                echo  $delimiter . '<a href="' . get_term_link($destination->term_id, 'destinations') . '">' . $destination->name . '</a>';
        }
        echo '<div class="page-name">' .get_queried_object()->name . '</div>';
        echo '<div>DESTINATION A '.get_term_meta(get_queried_object()->term_id,'aux_alentours-kilometrage',true).' KM DE PARIS</div>';

    }

    
    echo '</div></div>';
}
