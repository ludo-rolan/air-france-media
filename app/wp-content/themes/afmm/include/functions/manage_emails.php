<?php
class ManageEmails
{
    public static function register()
    {
        add_action('save_post', [self::class, 'emailing_hot_post'], 100, 3);
    }
    public static function emailing_hot_post($id, $post, $update)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (defined('REST_REQUEST') && REST_REQUEST) return;
        if (wp_is_post_revision($id)) return;
        if (wp_is_post_autosave($id)) return;
        if (!$update) return;
        if (get_option('enablehotpost') == '') return;

        $emails = get_option('emails');
        $emails != '' ? $list_emails = explode(';', $emails) : $list_emails = null;

        $post_meta = get_post_meta($id);
        $chaud = $post_meta['chaud_froid_type'][0];
        $sent = $post_meta['mail_sent'][0];

        $status = get_post_status($post);

        if ($list_emails != null && $chaud === "CHAUD" && $sent != 'sent' && $status == 'publish') {
            $post_url = get_permalink($id);
            $subject = 'EnVols : 1 nouveau contenu a été publié';
            $message = "Bonjour,\n";
            $message .= "Veuillez trouver le dernier contenu 'chaud' publié sur EnVols : ";
            $message .= $post_url;
            $message .= "\nL'équipe EnVols.";
            $is_sent = wp_mail($list_emails, $subject, $message);
            add_post_meta($id, 'mail_sent', 'sent');
        }
    }
    public static function emailing_streams()
    {
        $log = "NOUS SOMMES LE : " . date('d-m-Y');
        $log .= "\nréveiller l'émetteur du flux => " . date('h:i:sa');

        $emails = get_option('flux');
        $emails != '' ? $list_emails = explode(';', $emails) : $list_emails = null;

        $yesterday_date = date('d-m-Y', strtotime("-1 days"));

        $urls = [
            'daily_feed' => 'https://www.en-vols.com/daily-feed/?date=' . $yesterday_date . '&lang=fr',
            'hot_posts' => 'https://www.en-vols.com/flux-chaud/?date=' . $yesterday_date . '&lang=fr'
        ];

        $subject = "Les derniers articles publiés en : " . $yesterday_date;

        $message = "Bonjour,";
        $message .= "\nLes derniers articles publiés : " . $urls['daily_feed'];
        $message .= "\nLes derniers articles publiés de type Chaud : " . $urls['hot_posts'];
        $message .= "\nL'équipe EnVols";

        $is_sent = wp_mail($list_emails, $subject, $message);
        $is_sent == true ? $log .= "\nEnvoyé avec succès à : " . date('h:i:sa') : $log .= "\nCourriel non envoyé :O";

        update_option('fluxlogs', $log);
    }
}
ManageEmails::register();
