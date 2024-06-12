<?php 

class filtre_post_type
{
    var $filter_posts;
    function __construct(){
        add_action( 'restrict_manage_posts', array($this,'admin_posts_filter'));
        add_filter( 'parse_query', array($this,'posts_filter' ));
        add_filter('manage_posts_columns', array($this,'display_type_column_post'));
        add_action( 'manage_posts_custom_column', array($this,'type_column_post_content'), 10, 2 );
    }
//add dropdown liste for filtering posts
    function admin_posts_filter(){
        global $site_config;
        foreach($site_config['filter_post'] as $filter){
        if( isset ($_GET['post_type']) && $_GET['post_type']==$filter['post_type'] || (!isset($_GET['post_type']) && $filter['post_type']=='post')){
        $values =$filter['values'];
    ?>
        <select name="ADMIN_FILTER">
            <option value=""><?php echo $filter['title']; ?></option>
        <?php
        $current_v = isset($_GET['ADMIN_FILTER'])? $_GET['ADMIN_FILTER']:'';
        foreach ($values as $value=> $label ) {
            printf
            (
            '<option value="%s"%s>%s</option>',
            $value,
            $value == $current_v? ' selected="selected"':'',
            $label
            );
        }
        ?>
        </select>
    <?php
    }
        }
    }

//show posts having the display type mentionned

    function posts_filter( $query ){
        global $site_config;
        foreach($site_config['filter_post'] as $filter){
            if( isset ($_GET['post_type']) && $_GET['post_type']==$filter['post_type'] || (!isset($_GET['post_type']) && $filter['post_type']=='post')){
            if ( isset($_GET['ADMIN_FILTER']) && $_GET['ADMIN_FILTER'] != '') {
                $query->query_vars['meta_key'] = $filter['post_meta'];
                $query->query_vars['meta_value'] = $_GET['ADMIN_FILTER'];
            }
        } 
        }
    }
//add the column "post_display_type_name" to posts table

    function display_type_column_post( $columns ) {
        global $site_config;
        foreach($site_config['filter_post']as $filter){
            if( isset ($_GET['post_type']) && $_GET['post_type']==$filter['post_type'] || (!isset($_GET['post_type']) && $filter['post_type']=='post')){
            $columns[$filter['post_meta']] = $filter['title'];
            }

        }
        return $columns;
    }
//show the value of "post_display_type_name"

    function type_column_post_content( $column_name, $post_id ) {
        global $site_config;
        foreach($site_config['filter_post']as $filter){
            if( isset ($_GET['post_type']) && $_GET['post_type']==$filter['post_type'] || (!isset($_GET['post_type']) && $filter['post_type']=='post')){
            if( $column_name == $filter['post_meta'] ) {
            $type = get_post_meta( $post_id, $filter['post_meta'], true );
            echo $type;
        }
    }
        }
    }
}
new filtre_post_type();
