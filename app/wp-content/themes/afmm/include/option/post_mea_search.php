<?php
class Post_mea
{
    
    public static  function register()
    {
        add_action('wp_ajax_postmea', [self::class, 'postmea']);
        add_action('wp_ajax_nopriv_postmea',[self::class, 'postmea']);
    }
    
function postmea()
{
    $padding="text-align: center;padding:8px 10px 8px 0;";
    $term_id = $_POST['term_id'];
    if(!empty($_POST['term_id']) && !empty($_POST['keyword'])){
        $the_query = new WP_Query(
            array(
                'posts_per_page' => -1,
                's' => esc_attr($_POST['keyword']),
                'post_type' => 'post',
                'cat' => $term_id,
            )
        );
        
        if ($the_query->have_posts()) :
?>
<table class='widefat striped'>
<thead>
      <tr>
        <th style="<?php echo $padding ?>">Post id</th>
        <th style="<?php echo $padding ?>">Titre</th>
        <th style="<?php echo $padding ?>">Selectionner Article</th>
        <th style="<?php echo $padding ?>">Voir Article</th>
      </tr>
    </thead>
    <tbody>
    <?php
        $idtitle = array();
        while ($the_query->have_posts()) : $the_query->the_post();

            $myquery = esc_attr($_POST['keyword']);
            $a = $myquery;
            $search = get_the_title();
            if (stripos("/{$search}/", $a) !== false) { 
                 $idtitle[get_the_ID()] = get_the_title();
                ?>

                 <tr class="">
                  <td style="<?php echo $padding ?>"> <?php the_ID() ?> </td> 
                  <td style="<?php echo $padding ?>"><a href="javascript:copy_paste(<?php the_ID() ?>)" ><?php the_title(); ?></a></td>
                  <td style="<?php echo $padding ?>"> <button type="button" class ="button-primary" onclick="copy_paste(<?php the_ID() ?>)"> Sélectionner</a></td>
                  <td style="<?php echo $padding ?>"> <a class ="button-primary" href="<?php echo the_permalink(); ?>"> Voir</a></td> 
                  
                </tr>
<?php
            }
        endwhile;
        ?>
                <script type="text/javascript">  var idtitle = <?php echo json_encode($idtitle); ?>; </script>
        </tbody>
        </table>
<?php        
        wp_reset_postdata();
    endif;
}
    die();
}
}

Post_mea::register();
