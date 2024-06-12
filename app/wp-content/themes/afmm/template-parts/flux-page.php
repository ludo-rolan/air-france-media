<?php 
$current_lang = isset($_GET['lang'])? $_GET['lang']:'fr';
$date_input = isset($_GET['date'])? '<input type="hidden" name="date"  value="'.$_GET['date'].'"/>':'';
$type_input=isset($_GET['type'])? '<input type="hidden" name="type"  value="'.$_GET['type'].'"/>':'';
$number_input=isset($_GET['number'])? '<input type="hidden" name="number"  value="'.$_GET['number'].'"/>':'';
global $site_config;
?>
<div class="container page">
    <form action="" method="get">
        <?php  echo $date_input,$type_input,$number_input ?>
        <select class="page_lang form-select" name ="lang" onchange="this.form.submit()">
            <option <?php if($current_lang=='fr')  echo "selected"?>  value="fr">fr</option>
            <option <?php if($current_lang=='en')  echo "selected"?>  value="en">en</option>
        </select>
</form>
<div class="page_dailyfeed">
<img class="page_dailyfeed_img" src="<?php echo STYLESHEET_DIR_URI.'/assets/img/LogoEnvols-noir_v2.png'?>">
<?php
    global $sitepress;
    $sitepress->switch_lang($current_lang);
    $page=array();
    foreach($site_config["pages"] as $unepage){
        if ($unepage["pagename"]==$pagename){
            $page=$unepage;
            break;
        }
    };
    $args=$page['args'];
    $posts =get_posts( $args );
    if( $posts) {
        ?>
        <h2 class="page_dailyfeed_h1"><?php _e($page['titre'],AFMM_TERMS); ?></h2>
        <div class="row page_dailyfeed_row">
        <?php
        foreach($posts as $article ){
            global $post;
            $post=$article;
            ?>
			<div class="col-md-6 mt-5">
			    <?php include STYLESHEET_DIR . '/template-parts/home/post_preview_elem.php'; ?>
                <?php $terms = get_the_terms( get_the_ID(), 'destinations' );
                         
                         if ( $terms && ! is_wp_error( $terms )) : 
                          
                             $term_list = array();
                          
                             foreach ( $terms as $term ) {
                                 if( $term->parent ){
                                    $term_list[] = $term->name;
                                 }
                                }
                                    foreach($term_list as $dest){
                                    echo "<h4 class='post_cat page_dailyfeed_dest'>$dest </h4>";
                            }
                            endif;
                ?>
			</div>
		<?php
        } ?>
        </div>
        <?php
    } else {
        ?>
        <h2 class='page_dailyfeed_h1'>
        <?php _e($page['titre_noresults'],AFMM_TERMS) ?> 
        </h2>
        <?php
    }

?>
</div>
</div>