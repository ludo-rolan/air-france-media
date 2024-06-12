<?php
class Hp_General_Option
{

    public static  function register()
    {
        add_action('wp_ajax_data_fetch', [self::class, 'data_fetch']);
        add_action('wp_ajax_nopriv_data_fetch', [self::class, 'data_fetch']);
        add_action('hp_option_input_container', [self::class, 'hp_option_input_container']);
        add_action('hp_option_post_links_list', [self::class, 'hp_option_post_links_list'], 10, 3);
    }

    static function hp_option_input_container($type) {
        ?>
        <input type="hidden" name="type" id="type" value="<?php echo $type ?>"></input>
        <input type="text" name="keyword" id="keyword" onkeyup="fetch_hp_locking_search()"></input>
        <div id="datafetch">Résultats de recherche apparaîtront ici ...</div>
        <?php 
    }

    static function hp_option_post_links_list ($option, $post_type ,$do_get_posts) {
        if($do_get_posts) {
            $ids = explode(',', $option);
            if (count($ids) != 0 && $ids[0] != '') {
                $args = array(
                    'post__in' => $ids,
                    'orderby' => 'post__in',
                    'post_type' => $post_type,
                    'posts_per_page' => -1,

                );
                echo "<ul>";

                $posts = get_posts($args);
                foreach ($posts as $key => $post) {
                    echo "<li> <a href=" . admin_url('post.php?post=' . $post->ID) . '&action=edit' . ">"
                        . ($post->ID) . '-' . $post->post_title
                        . "</li>";
                }
                echo "</ul>";
            } 
        }
    }

    
    function data_fetch()
    {
        $padding="text-align: center; padding:25px 10px 5px 0;";
        $the_query = new WP_Query(
            array(
                'posts_per_page' => -1,
                's' => esc_attr($_POST['keyword']),
                'post_type' => esc_attr($_POST['type']),
                'suppress_filters' => 1,

            )
        );


        if ($the_query->have_posts()) :
?>
<style>
     .copy_paste[tooltip]:focus:before {
        content: attr(tooltip);
        display: block;
        position: relative;
        margin-top: -30px;
        color: #135e96;
    }
</style>
<table class='wp-list-table widefat striped fixed' style="width:50%;">
<thead>
      <tr>
        <?php 
            // Les titres sont définis dans chaque thème.
            do_action('hp_option_search_table_header',$padding);
        ?>
      </tr>
    </thead>
    <tbody>
        <?php
            while ($the_query->have_posts()) : $the_query->the_post();

                $myquery = esc_attr($_POST['keyword']);
                $a = $myquery;
                $search = get_the_title();
                if (stripos("/{$search}/", $a) !== false) { ?>
                  <tr class="">
                    <td style="<?php echo $padding ?>"> <?php the_ID() ?> </td> 
                    <td style="<?php echo $padding ?>"><a tooltip="ID copié"  class="copy_paste" href="javascript:copy_paste_to_clipboard(<?php the_ID() ?>)" ><?php the_title(); ?></a></td>
                    <td style="<?php echo $padding ?>"> <button tooltip="ID copié" type="button" class =" copy_paste button-primary" onclick="copy_paste_to_clipboard(<?php the_ID() ?>)"> Copier</button></td>
                    <td style="<?php echo $padding ?>"> <a class ="button-primary" href="<?php echo the_permalink(); ?>"> Voir</a></td> 
                  </tr>
<?php
                }
            endwhile;
            ?>
            </tbody>
            </table>
    <?php        
            wp_reset_postdata();
        endif;

        die();
    }
}

Hp_General_Option::register();
