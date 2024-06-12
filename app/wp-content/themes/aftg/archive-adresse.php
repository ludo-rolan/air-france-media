<?php

get_header();
global $site_config;
$filtres = get_terms(['taxonomy' => 'filtre', 'parent' => 0]);
$tax = isset($_GET['destinations_id']) ? 'destinations' : (isset($_GET['aux_alentours_id']) ? 'aux_alentours' : '');
$term = get_term($_GET[$tax.'_id'], $tax);
$term_id = $term->term_id;
$term_param = $tax.'_id='.$term_id;
$filtre = get_query_var('af_filtres');
$term_parent = get_term_by('id', $term->parent, $tax);
$paged = get_query_var('paged') ?: 1;
// chaque requete a une couche cache : example de key cache => adresses_bloc_202_tout_voir
$cache_key = $site_config['aftg_cache']['adresses_block']['key'].'_'.$term_id.(empty($filtre)?'':'_'.$filtre).'_'.$paged;
$cache_time = $site_config['aftg_cache']['adresses_bloc']['time'];
$query_conf = [
    'post_type' => "adresse",
    'posts_per_page' => 9,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC'
];
if ($filtre != 'tout-voir') {
    $query_conf['tax_query'] = [
        'relation' => 'AND',
        [
            'taxonomy' => $tax,
            'field' => 'term_id',
            'terms' => $term_id
        ],
        [
            'taxonomy' => 'filtre',
            'field' => 'slug',
            'terms' => $filtre
        ]
    ];
} else {
    $query_conf['tax_query'] = [
        [
            'taxonomy' => $tax,
            'field' => 'term_id',
            'terms' => $term_id
        ]
    ];
}
$query = new WP_Query($query_conf);
if (isset($_GET['show_query'])) {
	var_dump($query_conf);
	var_dump('====================');
	print_r($query->request);
}
?>
<!-- breadcrumb + ad -->
<section>
    <div class="container">
        <?php echo do_shortcode("[dfp id=" . $site_config['dfp-position']['archive-adresse']['ad1'] ."]") ;?>
        <?php include(locate_template('template-parts/components/breadcrumb_adresses.php')) ?>
    </div>
</section>
<div class="container">
    <div class="subheader">
        <div class="title">
            <h1 class="destination_title_adresse"><?php _e('nos adresses à', AFMM_TERMS) ?> <?php echo $term->name ?></h1>
        </div>
        <?php include(locate_template('template-parts/components/subnav.php')) ?>
    </div>
    <div class="content">
        <div class="row">
            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post() ?>
                    <?php
                    $link_destination = get_permalink();
                    $id_destination = get_the_ID();
                    $destination_meta =  get_post_meta($id_destination);
                    $topics = get_the_terms($adresses[$i]->ID,'post_tag');
                    $title_destination = join(' - ', wp_list_pluck($topics, 'name'));
                    $destination_partenaire = $destination_meta['partenaire_partenaire'][0];
                    $image_html = get_the_post_thumbnail($id_destination, 'large', ["class" => "post_image"]);
                    $title2_destination = get_the_title();
                    $preview_type = 'adresse';
                    ?>
                    <div class="col-md-4 post">
                        <a class="carnet_badge post_card_badge" data-id="<?php echo $id_destination; ?>"></a>
                        <?php include(locate_template('/template-parts/country/preview_destination_post.php')) ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; wp_reset_postdata();?>
        </div>
        <?php do_action( 'aftg_pagination', $query, $paged); ?>
    </div>
</div>
<?php
get_footer();
?>