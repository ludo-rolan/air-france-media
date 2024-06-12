function fetch_mea() {
    if (jQuery('#keyword').val() && jQuery('#keyword').val().length > 2) {
        jQuery.ajax({
            url: data.ajax_url,
            type: 'POST',
            data: {
                action: 'postmea',
                keyword: jQuery('#keyword').val(),
                term_id: data.term_id
            },
            success: function(data) {
                jQuery('#datafetch').html(data);
            }
        });
    } else if (jQuery('#keyword').val() && jQuery('#keyword').val().length == 0) {
        jQuery('#datafetch').html("Résultats de recherche apparaîtront ici ...");
    }

}

function copy_paste(id) {
    jQuery('#post_id').val(id);
    jQuery('#keyword').val(idtitle[id]);
}
