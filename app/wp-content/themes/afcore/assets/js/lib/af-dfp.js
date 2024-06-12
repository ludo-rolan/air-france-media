function setCookie(cname, cvalue, exminutes) {
    var d = new Date();
    d.setTime(d.getTime() + (exminutes * 60 * 1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function send_GA(category, action, lebel, value) {
        if (window._gaq) {
            if (value) {
                _gaq.push(['_trackEvent', category, action, lebel, value]);

            } else {
                _gaq.push(['_trackEvent', category, action, lebel]);
            }

        } else if (window.gtag) {
            if (value) {
                gtag('event', action, {
                    'event_category': category,
                    'event_label': lebel,
                    'value': value
                });
                if (site_config_js.other_google_analytics_ids) {
                    jQuery.each(site_config_js.other_google_analytics_ids, function(index, value1) {
                        gtag('event', action, {
                            'send_to': value1,
                            'event_category': category,
                            'event_label': lebel,
                            'value': value
                        });
                    });
                }
            } else {
                gtag('event', action, {
                    'event_category': category,
                    'event_label': lebel
                });
                if (site_config_js.other_google_analytics_ids) {
                    jQuery.each(site_config_js.other_google_analytics_ids, function(index, value1) {
                        gtag('event', action, {
                            'send_to': value1,
                            'event_category': category,
                            'event_label': lebel
                        });
                    });
                }
            }
        } else {
            if (value) {
                ga('send', 'event', category, action, lebel, value);
                if (site_config_js.other_google_analytics_ids) {
                    for (var ga_name in site_config_js.other_google_analytics_ids) {
                        ga(ga_name + '.send', 'event', category, action, lebel, value);
                    }
                }
            } else {
                ga('send', 'event', category, action, lebel);
                if (site_config_js.other_google_analytics_ids) {
                    for (var ga_name in site_config_js.other_google_analytics_ids) {
                        ga(ga_name + '.send', 'event', category, action, lebel);

                    }
                }
            }
        }
    

}
function send_events_GA(category, action, lebel) {
    if (window.gtag) {
        gtag('event', action, {
            'send_to': 'events',
            'event_category': category,
            'event_label': lebel
        });
    } else {
        ga('events.send', 'event', category, action, lebel);
    }
}

function pageview_GA(only_path_and_hash) {

    if (window._gaq) {
        window._gaq.push(['_trackPageview', only_path_and_hash]);
    } else if (window.gtag) {

        gtag('event', 'page_view', {
            'send_to': site_config_js.google_analytics_id
        });
        if (site_config_js.other_google_analytics_ids) {
            jQuery.each(site_config_js.other_google_analytics_ids, function(index, value1) {
                gtag('event', 'page_view', {
                    'send_to': value1
                });
            });
        }

    } else {
        if (typeof site_config_js["partners"] !== 'undefined' && site_config_js["partners"].analytics == true) {
            ga('send', 'pageview', only_path_and_hash);

            if (site_config_js.other_google_analytics_ids) {
                for (var ga_name in site_config_js.other_google_analytics_ids) {
                    ga(ga_name + '.send', 'pageview', only_path_and_hash);
                }
            }
        }
    }
}
function getCookie(cname) {
    var name = cname + "=";
    try{
    	var decodedCookie = decodeURIComponent(document.cookie);
	}catch( e){
	    var decodedCookie = document.cookie;
	}
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function display_dfp_pub_onscroll(tag_div, format_id ){
	// Valeur de scrollY lorsque l'annonce est sur le point d'être visible.
	site_config_js.dfp_elemnts = site_config_js.dfp_elemnts || {} ;
	var $element = jQuery('#'+tag_div); 



	  // Avertissement : Il s'agit d'un exemple de mise en œuvre. Écouter l'événement onscroll 
	  // sans fonction throttle peut ne pas être très efficace.
	  site_config_js.dfp_refreshed = site_config_js.dfp_refreshed || {} ;
	  var listener = function() { 
	  	var scroll = window.scrollY + jQuery(window).height() ;
	  	if ((scroll >= $element.offset().top - 100 ) && (scroll >= $element.parent().offset().top - 100 )  && ! site_config_js.dfp_refreshed[format_id]) {
	  		
	  		
	  			dfp_refresh_lazy_load_js (tag_div, format_id);
	  		
	  		

	      	// Actualiser l'annonce une seule fois
	      	site_config_js.dfp_refreshed[format_id] = true;	
	  		jQuery(document).ready(function(){
				if(typeof adblock != 'undefined' && !adblock){
					if( site_config_js.manage_tracking_ga){ 
						if( site_config_js.manage_tracking_ga.indexOf(format_id) != -1 ){ 
							send_GA( "DFP", format_id, self.location.href);
						}
					}else{ 
						send_GA( "DFP", format_id, self.location.href);
					}
				}else{
					if( !site_config_js.disable_dfp_adblocker_event ){
						send_GA( "DFP Adblocker", format_id, self.location.href);
					}
				}	
	  		});



	      // Supprimer l'écouteur
	      window.removeEventListener('scroll', listener);
	  }
	}
	window.addEventListener('scroll', listener);
}

jQuery(document).on("change_item" , function(e , i, rafraichir_pub) {

	if(rafraichir_pub && window.googletag){
		dfp_refresh_all_ads();
		var dfp_ids = site_config_js.dfp_ids ;
		for (i in dfp_ids){
			var id = dfp_ids[i];

			if(typeof adblock != 'undefined' && !adblock){
				if( site_config_js.manage_tracking_ga){ 
					if( site_config_js.manage_tracking_ga.indexOf(id) != -1 ){ 
						setTimeout(function(){ 
							send_GA( "DFP", id, self.location.href);
						}, 3000);
					}
				}else{ 
					setTimeout(function(){ 
						send_GA( "DFP", id, self.location.href);
					}, 3000);
				}
			}else{
				if( !site_config_js.disable_dfp_adblocker_event ){
					setTimeout(function(){ 
						send_GA( "DFP Adblocker", id, self.location.href);
					}, 3000);
				}
			}	
			
		}

	}
});

jQuery(document).ready(function() {
	if(window.googletag){
		googletag.cmd.push(function() {
		    googletag.pubads().addEventListener('slotRenderEnded', function(event) {
		        // Récupérer l'id du div contenant la pub
		        var id_div = event.slot.getSlotId().getDomId();
		        jQuery('#' + id_div).addClass('filled-with-pub');
		    });
		});
	}
});

setInterval(function () {
    dfp_refresh_all_ads();
}, 20 * 1000);




