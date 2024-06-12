<?php

class Destination_filtre_post_type
{
    function __construct()
    {
        add_action('restrict_manage_posts', array($this, 'admin_posts_filter'));
        add_filter('parse_query', array($this, 'posts_filter'));
    }
    //add dropdown liste for filtering posts
    function admin_posts_filter()
    {

        $terms_arr = [];
        $whole_destinations = get_terms('destinations');
        $terms = array_filter($whole_destinations, function ($t) {
            # This term has a parent, but its parent does not., oh it's a country !
            return $t->parent != 0 && get_term($t->parent, 'destinations')->parent == 0;
        });
     
        if (isset($_GET['post_type']) && $_GET['post_type'] == 'destination') {
?>
            <select name="PAYS">
                <option value=""><?php echo "PAYS" ?></option>
                <?php
                $current_v = isset($_GET['PAYS']) ? $_GET['PAYS'] : '';
                foreach ($terms as $term) {
                    printf(
                        '<option value="%s"%s>%s</option>',
                        $term->term_id,
                        $term->term_id == $current_v ? ' selected="selected"' : '',
                        $term->name
                    );
                }
                ?>
            </select>
<?php
        }
    }

    //show posts having the country mentionned

    function posts_filter($query)
    {
        if (isset($_GET['post_type']) && $_GET['post_type'] == 'destination') {
            if (isset($_GET['PAYS']) && $_GET['PAYS'] != '') {

                $children = get_term_children($_GET['PAYS'], 'destinations');
                $taxquery = array(
                    array(
                        'taxonomy' => 'destinations',
                        'field' => 'id',
                        'terms' => $children,
                        'operator' => 'IN'
                    )
                );

                $query->set('tax_query', $taxquery);
                // Defaults to ‘DESC’ so let's switch it to ASC
                $query->set('order','ASC');
            }
        }
    }
}
new Destination_filtre_post_type();
