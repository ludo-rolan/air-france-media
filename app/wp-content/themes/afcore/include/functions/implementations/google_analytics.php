<?php

class google_analytics extends rw_partner
{

    /**
     * Ce script est inséré sur l’ensemble des pages
     *
     * */

    function google_analytics_implementation()
    {
        global $site_config_js;
        $gtags = $this->get_param('google_analytics_gtags');
        foreach ($gtags as $gtag) {
            $script_ga = <<<GA
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=$gtag"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', '$gtag' );
            </script> 
GA;

            echo $script_ga;
            if (is_array($gtags) && count($gtags)) {
                unset($gtags['gtag']);
                $site_config_js['other_google_analytics_ids'] = $gtags;
            }
        }
    }
}
