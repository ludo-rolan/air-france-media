<?php

/**
 * add Nginx Cache admin menu
 */
function register_nginx_cache_menu_page()
{
    add_menu_page('Nginx Cache', 'Nginx Cache', 'manage_options', 'nginx-cache', 'module_nginx_cache', 'dashicons-admin-generic', 5);
}
add_action('admin_menu', 'register_nginx_cache_menu_page');

/**
 * Module BO to purge nginx cache
 * @return [type] [description]
 */
function module_nginx_cache()
{

?>
    <hr><br>
    <strong>Interface pour purger le cache nginx</strong>
    <hr><br>
    <div class="metabox-prefs">
        <form action="" method="post">

            <div class="form-group">
                <label for="source_blog">List des urls (url par ligne)</label> <br>
                <textarea name="urls" rows="10" cols="150"></textarea>
            </div>
            <input type="submit" name="ok" value="Purge Cache Nginx" class="button button-primary button-large">
        </form>
    </div>
    <?php
    if (isset($_POST['ok']) && !empty($_POST['urls'])) {
        $urls = explode(PHP_EOL, $_POST['urls']);
        foreach ($urls as $url) {
            //Check if a valid url
            $url = wp_http_validate_url(trim($url));
            if ($url) {
                $parse_url = parse_url($url);
                $home_url = $parse_url['scheme'] . '://' . $parse_url['host'] . '/';
                $url_to_purge = str_replace($home_url, $home_url . 'purge/', $url);

                wp_remote_get($url_to_purge);
                $ch = curl_init($url_to_purge);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                if (IS_PREPROD) {
                    curl_setopt($ch, CURLOPT_USERPWD, 'reworldmedia:reworldmedia');
                }
                curl_exec($ch);
                curl_close($ch);
            }
        }
    ?>
        <hr>
        <div>
            <p>
                Le cache nginx des urls est purgé avec succès.
            </p>
        </div>
<?php
    }
}
