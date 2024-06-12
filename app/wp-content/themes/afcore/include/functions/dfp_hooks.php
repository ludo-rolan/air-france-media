<?php
//Fichier contentant les filtre et les action utilisés dans dfp.php .

add_filter('dfp_article_type', 'dfp_article_type');


/* get the type of the article to do a ciblage with key article_type */
function dfp_article_type($post_id)
{
    if (PROJECT_NAME == "afmm") {
        //edito , long form , diapo 
        return     strtolower(get_post_meta(get_the_ID(), "post_display_type_name", true));
    } else {
        return get_post_type($post_id);
    }
}
