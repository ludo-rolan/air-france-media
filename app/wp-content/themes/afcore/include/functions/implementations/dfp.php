<?php /*
* @author : Abdellatif
* Projet Intégration DFP dans le Network 
*/
class dfp  extends rw_partner
{

	private $mpu_mobile_inited = false;
	private $count_displayed_mobile_formats = 0;
	private $ad_id=0;
	public function get_formats_dfp()
	{
		
$formats = $this->get_param('formats', array(
	'habillage' => array( 'opt_div' => 'dfp_habillage', 'condition' => 'is_desktop', 'sizes' => [1, 1]),
	'masthead_haut'	=> array('position'=>1 ,  'opt_div' => 'masthead_haut', 'condition'=>'is_desktop' , 'sizes' => [[970,250]]),
	'new_masthead_1'	=> array(  'opt_div' => 'masthead_haut', 'condition'=>'is_desktop' , 'sizes' => [[970,250]]),
	'new_masthead_2'	=> array(   'opt_div' => 'masthead_haut', 'condition'=>'is_desktop' , 'sizes' => [[970,250]]),
	'masthead_bas'	=> array('position'=>9 , 'opt_div' => 'dfp_masthead_bas', 'condition'=>'is_desktop' , 'sizes' => [[970,250]]),

	'mpu_haut'	=> array('position' => 2, 'opt_div' => 'dfp_mpu_haut', 'condition' => 'is_tablet || is_desktop', 'sizes' => [ [300, 600]]),
	'mpu_milieu'	=> array('position' => 3, 'opt_div' => 'dfp_mpu_milieu', 'condition' => 'is_tablet || is_desktop','sizes' => [[300, 250],[300,600]]),
	'mpu_milieu_2' 	=> array('position'=>3 , 'opt_div' => 'div-gpt-ad-1501321489821-9', 'condition'=>'is_desktop' , 'sizes'=>[[970,250]]),
	'mpu_milieu_3' 	=> array('position'=>4 , 'opt_div' => 'div-gpt-ad-1501321489821-10', 'condition'=>'is_desktop' , 'sizes'=>[[300,600]]),
	'mobile_1' => array('position' => 101, 'opt_div' => 'dfp_mobile_1', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_2' => array('position' => 102, 'opt_div' => 'dfp_mobile_2', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_3' => array('position' => 103, 'opt_div' => 'dfp_mobile_3', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_4' => array('position' => 104, 'opt_div' => 'dfp_mobile_4', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_5' => array('position' => 105, 'opt_div' => 'dfp_mobile_5', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_6' => array('position' => 106, 'opt_div' => 'dfp_mobile_6', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_7' => array('position' => 107, 'opt_div' => 'dfp_mobile_7', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_8' => array('position' => 108, 'opt_div' => 'dfp_mobile_8', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_9' => array('position' => 109, 'opt_div' => 'dfp_mobile_9', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_10' => array('position' => 110, 'opt_div' => 'dfp_mobile_10', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_11' => array('position' => 111, 'opt_div' => 'dfp_mobile_11', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),
	'mobile_12' => array('position' => 112, 'opt_div' => 'dfp_mobile_12', 'condition' => 'is_mobile', 'sizes' => [[300, 250],[300,600],[336, 280]]),

	'native' => array('position'=>23, 'opt_div' => 'dfp_native', 'condition' => 'is_desktop', 'sizes'=>[[300,250],[1,2],'fluid'], 'lazyload' => false ),
	'native_mobile' => array('position'=>23, 'opt_div' => 'dfp_native_mobile', 'condition' => 'is_mobile', 'sizes'=>[[300,250],[1,2],'fluid'], 'lazyload' => false ),
	'vignette_haut'	=> array('position'=>10 , 'opt_div' => $this->dfp_adunit_id(), 'condition'=>'is_desktop' , 'sizes'=>[[300,250], [300,125], [300,100], [300,150], 'fluid']),
	
));

		return apply_filters('initial_dfp_v2_formats', $formats);
	}


	function init()
	{
		global $wp_query;
		global $site_config;

		$wp_query->get_queried_object_id();

		$this->formats = $this->get_formats_dfp();

		add_action('wp_head', array($this, 'dfp_head'));
		add_action('wp_head', array($this, 'preconnect_dfp'));

		add_action('wp_head', array($this, 'wp_head_function_display_js'), 6);

		$page_type = $this->get_page_type();
		if ($page_type == 'rg_diapo') {
			add_action('single_after_excerpt', function () {

				echo do_shortcode("[dfp_v2 id='banner_incontent_1']");
			}, 2);
		}


		if ($site_config['ismobile']) {
			if (is_single()) {
				add_action('dmm_after_article_intro', array($this, 'insert_pub_dfp_mobile'));
				add_action('dmm_after_gallery', array($this, 'insert_pub_dfp_mobile'), 9);
				add_action('dmm_after_gallery', array($this, 'insert_pub_dfp_mobile'), 11);
			}
			$this->init_mpu_mobile();
			$this->init_dfp_mobile();
		}
		$this->init_dfp_mobile();
		wp_enqueue_script('jquery-ismobile', AF_THEME_DIR_URI.'/assets/js/lib/ismobile.min.js', array('jquery'), CACHE_VERSION_CDN, false );

		wp_enqueue_script('dfp-js', AF_THEME_DIR_URI . '/assets/js/lib/af-dfp.js', array("jquery"), CACHE_VERSION_CDN);

		do_action('init_dfp_v2');
	}
	function dfp_adunit_id(){
		$this->ad_id += 1;
		 return "div-gpt-ad-1501321489821-".$this->ad_id;
	 }
	function init_dfp_mobile()
	{
		global $wp_query;
		// Hooks for single pages
		if (is_single()) {

			if (!$this->get_param('mpu_mobile_test_active')) {
				add_filter('filter_after_breadcrumb', function ($breadcrumb) {
					$this->count_displayed_mobile_formats++;
					if ($this->count_displayed_mobile_formats <= 12) {
						$breadcrumb .= do_shortcode($this->insert_place_dfp_mobile());
					}
					return $breadcrumb;
				});
			} else {
				$this->count_displayed_mobile_formats++;
			}

			
		}
		// End hooks for single pages

	}

	public function wp_head_function_display_js()
	{
		$function_display_js = '
		
        <script type="text/javascript">
         function dfp_display_js (div_id, format_name ){
            googletag.cmd.push(function() { 
                googletag.display(div_id);
            });
        } 
        function dfp_refresh_lazy_load_js (div_id, format_name ){
            console.log("%c => Refreshing with Lazyloading DFP DEFAULT for "+ format_name,"background: #; color: #000000");
            googletag.cmd.push(function() {
                googletag.pubads().refresh( [site_config_js.dfp_slots[format_name] ]) ; 
            });
            site_config_js.dfp_refreshed = site_config_js.dfp_refreshed || {} ;
            site_config_js.dfp_refreshed[format_name] = true;
        } 
        function dfp_refresh_all_ads(exclude){
            //googletag.pubads().refresh();
            var slots = [];
            for(format in site_config_js.dfp_refreshed){
                if(!exclude || exclude.indexOf(format) == -1 ){
                    slots.push(site_config_js.dfp_slots[format]);
                    console.log("%c => Refreshing "+ format +" ads by DFP DEFAULT REFRSH","background: #; color: #000000");
                }
            }
            if(slots.length){
                googletag.pubads().refresh(slots);
            }
        }
        function dfp_show_one_slot ( format_name ){
            googletag.cmd.push(function() { 
                if(site_config_js.dfp_slots[format_name]){
                    console.log("%c => SHOWING single ad with DFP DEFAULT for "+ format_name,"background: #; color: #ffffff");
					googletag.cmd.push(function() { 
						googletag.cmd.push(function() {
							setTimeout(function() {
								googletag.pubads().refresh([site_config_js.dfp_slots[format_name] ]) ; 
							}, 700);
						});
					});
                }
            });
        }
        
        </script>';

		echo apply_filters('dfp_function_display_js', $function_display_js);
	}

	//detecter le type de la page, (hp, rg ou diapo_monetisation ...)
	function get_page_type()
	{
		global $post;

		$bloc_annonces = $this->get_bloc_annonces();
		if (isset($bloc_annonces['type_page'])) {
			return $bloc_annonces['type_page'];
		}

		$pages_types = $this->get_param('pages_types');

		$page_type = apply_filters('dfp_page_type', false, $pages_types);
		if ($page_type) {
			return $page_type;
		}


		if(is_single() && isset($pages_types['diapo_monetisation']) && get_post_meta($post->ID, 'post_display_type_name', true)=="DIAPO"){
			return 'diapo_monetisation' ;
		}

		if (is_search() && isset($pages_types['search'])) {
			return 'search';
		}

		if (is_archive() && isset($pages_types['hp_rubrique'])) {
			return 'hp_rubrique';
		}

		

		if (is_home() || is_archive() || is_front_page()) {
			return 'hp';
		} else {
			return 'rg';
		}
	}

	// recuperer le liste des formats dispos 
	function get_tags()
	{

		if (isset($this->tags)) {
			return $this->tags;
		}

		$page_type = $this->get_page_type();
		$pages_types = $this->get_param('pages_types');

		$this->tags = array();
		$tag_keys =  isset($pages_types[$page_type]) ? $pages_types[$page_type] : array();

		foreach ($tag_keys as $key) {
			if (isset($this->formats[$key])) {
				$active_format = true;
				if (!empty($_GET['desactive_partners'])) {
					$desactive_partners = $_GET['desactive_partners'];
					$desactive_partners = explode(',', $desactive_partners);
					if (in_array($key, $desactive_partners)) {
						$active_format = false;
					}
				}
				$active_format = apply_filters('activate_dfp_format', $active_format, $key);
				if ($active_format) {
					$this->tags[$key] = $this->formats[$key];
				}
			} else {
				// Merci de ne pas commenter ou supprimer cette ligne !
				echo 'Tag non existe : ' . $key . '<br>';
			}
		}
		// gérer l'affichage ou pas de certains tags
		$this->tags = apply_filters('tags_dfp_displayed', $this->tags);
		return $this->tags;
	}

	//recuperer le block d'annonces
	function get_bloc_annonces()
	{
		global $wp_query;
		if (isset($this->bloc_annonces) && $this->bloc_annonces !== null) {
			return $this->bloc_annonces;
		}

		$plan_tagagge = $this->get_param('plan_tagagge');

		$keys = array();
		if (is_home() || is_front_page()) {
			$keys  = array(apply_filters('locale_cat', 'hp'));
		} elseif ((is_home() || is_front_page()) && !empty($wp_query->query['post_type'])) {
			if ($wp_query->query['post_type']  == "folder") {
				$keys =  array('folder', 'divers');
			} else {
				$keys = array($wp_query->query['post_type']);
			}
		} else if (is_category()) {

			$category = $wp_query->queried_object;


			$cat_tree = get_category_parents($category->term_id, FALSE, ':', TRUE);
			//Fix warning when $cat_tree it's not a string
			if (is_string($cat_tree)) {
				$cat_tree = trim($cat_tree, ':');
				$cat_tree = apply_filters('category_parents_tree', $cat_tree);
				$list_cat = explode(':', $cat_tree);
				for ($i = count($list_cat) - 1; $i >= 0; $i--) {
					$keys[] = $list_cat[$i];
				}
			}
		} else if (is_single()) {
			$post_type = false;
			if (!empty($wp_query->query['post_type']) && $wp_query->query['post_type'] != 'post') {
				$post_type = $wp_query->query['post_type'];
			}
			
			if (!empty($wp_query->query['post_type']) && $wp_query->query['post_type'] == 'test') {
				$post_type = $wp_query->query['post_type'];
			}

			if ($post_type) {
				if (isset($list_cat)) {
					for ($i = count($list_cat) - 1; $i >= 0; $i--) {
						$keys[] = $post_type . '_' . $list_cat[$i] . "_RG";
					}
				}

				$keys[] =  $post_type . '_RG';
			}

			if (isset($list_cat)) {
				for ($i = count($list_cat) - 1; $i >= 0; $i--) {
					$keys[] = $list_cat[$i] . "_RG";
				}
			}
		} else if (is_tax()) {
			$queried_object  = $wp_query->queried_object;
			$keys[] =  $queried_object->slug;
			$keys[] =  'tax_' .  $queried_object->taxonomy;
		}
		$keys[] = apply_filters('locale_cat', 'divers');
		foreach ($keys as $key) {
			if (isset($plan_tagagge[$key])) {
				$this->bloc_annonces = $plan_tagagge[$key];
				return $this->bloc_annonces;
			}
		}

		$this->bloc_annonces = false;
		return $this->bloc_annonces;
	}


	//preparer la partie header du tag 
	function dfp_head()
	{
		global $wp_query;

		$dfp_id = $this->get_param('dfp_id_account');

		$dfp_tags = $this->get_tags();
		$bloc_annonces = $this->get_bloc_annonces();
		$slot = '';


		if (count($dfp_tags) && $bloc_annonces) {
			$bloc_annonces_id = $bloc_annonces['id'];

			$sas_target = apply_filters('sas_target', array(), array());
			$sas_target = array_unique($sas_target);
			$sas_target = implode(';', $sas_target);
			$sas_target = explode(';', $sas_target);
			$dfp_targeting = apply_filters('dfp_targeting', array());
			foreach ($sas_target as $value) {
				if ($value) {
					$cle_valeur = explode("=", $value);
					if (count($cle_valeur) == 2 && !empty($cle_valeur[0]) && !empty($cle_valeur[1])) {
						//	$dfp_targeting [$cle_valeur[0]][] = RW_Utils::rw_remove_accents($cle_valeur[1]) ;
					}
				}
			}
			$limit_etiquettes = (empty($_GET['limit-etiquettes']) ? 20 : $_GET['limit-etiquettes']);
			if (is_single()) {
				$dfp_targeting['article_id'][] = get_the_ID();
				$dfp_targeting['article_type'][] =apply_filters('dfp_article_type',get_the_ID());
				$posttags = get_the_tags();
				if (is_array($posttags) && count($posttags)) {
					$inedx_etiquettes = 0;
					foreach ($posttags as $tag) {
						$inedx_etiquettes++;
						$dfp_targeting['Etiquettes'][] = $tag->slug;
						if ($limit_etiquettes > -1 &&  $limit_etiquettes == $inedx_etiquettes) {
							break;
						}
					}
				}

				$permalinked_category = Af_Category_Helper::get_menu_cat_post_old(get_the_ID());
				if(!empty($permalinked_category->term_id)){
					$rubrique_id = $permalinked_category->term_id ;
					$category_parents = Af_Category_Helper::reworld_get_category_parents_ids($rubrique_id);
					$cats_parents = array_filter(array_map('intval', explode('/', $category_parents)));
					$dfp_targeting['rubrique_id'] = $cats_parents;
				}
			}
			//Ajouter les rubrique ciblage sur les pages category
			if(is_category()){																						
				$queried_object = get_queried_object();														
				if(!empty($queried_object)){	
					$rubrique_id = $queried_object->term_id;
					$category_parents = Af_Category_Helper::reworld_get_category_parents_ids($rubrique_id);
					$cats_parents = array_filter(array_map('intval', explode('/', $category_parents)));			
					$dfp_targeting['rubrique_id'] = $cats_parents;
				} 
			}

			$dfp_div_ids = [];
			
			foreach ($dfp_tags as $id => $pub) {
				
				$tag_size = json_encode($pub['sizes']);
			
				$opt_div = $pub['opt_div'];
				$dfp_div_ids[$id] = $opt_div;
				
				if (isset($pub['dfp_id_account'])) {
					$dfp_id_account = $pub['dfp_id_account'];
				} else {
					$dfp_id_account = $dfp_id;
				}
				if (isset($pub['bloc_annonce_level2'])) {
					$dfp_bloc_annonces_id = $pub['bloc_annonce_level2'];
				} else {
					$dfp_bloc_annonces_id = $bloc_annonces_id;
				}

				$dfp_bloc_annonces_id = $dfp_bloc_annonces_id ? '/' . $dfp_bloc_annonces_id : '';
				
				$defineSlot = " / * $id */ \n gptadslots.push(";
				if ($pub['sizes'] != 'out-of-page') {
					$defineSlot .= "site_config_js.dfp_slots['$id'] = googletag.defineSlot('/$dfp_id_account$dfp_bloc_annonces_id', $tag_size, '$opt_div')";
				} else {

					$defineSlot .= "site_config_js.dfp_slots['$id'] = googletag.defineOutOfPageSlot('/$dfp_id_account$dfp_bloc_annonces_id', '$opt_div')";
				}
				if (isset($pub['position'])) {
					$position = $pub['position'];
					$defineSlot .= ".setTargeting('pos', ['$position'])";
				}
				$defineSlot .= ".setTargeting('visite', [visite])";
				if (IS_PREPROD) {
					$defineSlot .= ".setTargeting('preprod', ['true'])";
				} else {
					//$defineSlot .= ".setTargeting('preprod', ['false'])";
				}

				if (count($dfp_targeting)) {
					foreach ($dfp_targeting as $key_targeting => $value_targeting) {
						$value_targeting = json_encode($value_targeting);
						$value_targeting = str_replace('","', '", "', $value_targeting);
						$defineSlot .= ".setTargeting('$key_targeting', $value_targeting)";
					}
				}

				$defineSlot .= ".setCollapseEmptyDiv(true).addService(googletag.pubads())); \n";
				// Limite ( is_desktop..) 

				if (isset($pub['condition'])) {
					$slot .= 'if (' . $pub['condition'] . ") { \n" . $defineSlot . '}';
				} else {
					$slot .= $defineSlot;
				}
			}

			$dfp_js = apply_filters('dfp_append_to_js', '');

			$formats_lazyloading = $this->get_formats_lazyloading();
			$disableInitialLoad = apply_filters('dfp_v2_disableInitialLoad', count($formats_lazyloading));


			if ($disableInitialLoad) {
				$slot .= "\n googletag.pubads().disableInitialLoad(); \n";
			}

			$dfp_div_ids = json_encode($dfp_div_ids);

			$script_gpt = apply_filters(
				'dfp_gpt_custom_script',
				"<script async='async' src='https://securepubads.g.doubleclick.net/tag/js/gpt.js'></script>"
			);

			$s = <<<DFPTAG
			<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
			<script type='text/javascript'>
	            var visite = 0;
	            if(getCookie('visite')){
	                visite=0;
	            }else{
	                setCookie('visite',1,30);
	                visite=1;
	            }
	 		  var gptadslots = [];
			  var googletag = googletag || {cmd:[]};
			  site_config_js.dfp_slots = site_config_js.dfp_slots || {} ;
              site_config_js.dfp_refreshed = site_config_js.dfp_refreshed || {} ;
			  site_config_js.dfp_div_ids = $dfp_div_ids  ;
			  googletag.cmd.push(function() {
			  	$slot
				googletag.pubads().enableSingleRequest();
	     		googletag.pubads().collapseEmptyDivs();
    			googletag.pubads().setCentering(true);
DFPTAG;
			$formats_lazyloading = $this->get_formats_lazyloading();
			$dfp_tags_keys = array_keys($dfp_tags);

			$slots_to_refresh = array();

			foreach ($dfp_tags_keys as $dfp_tag) {
				if ($formats_lazyloading && !in_array($dfp_tag, $formats_lazyloading)) {
					$slots_to_refresh[] = $dfp_tag;
				}
			}


			$after_enableServices = apply_filters('dfp_v2_after_enableservices', '', $slots_to_refresh, $formats_lazyloading);

			$s .= $after_enableServices;

			$s .= <<<DFPTAG
				googletag.enableServices();
				$dfp_js
			  });
			</script>
			<style type="text/css">
			div.pub_dfp > div {
				    display: block !important;
				}
			</style>
DFPTAG;
			echo $s;
		}
	}

	// shortcode pour afficher la pub sur le widget
	function dfp_short_code($atts)
	{   

		$dfp_tags = $this->get_tags();
		$id = isset($atts['id']) ?  $atts['id'] : '';
		$sticky = isset($atts['sticky']) && $atts['sticky'] == 'yes' ?  'scroll_to_fixed' : '';
		if(isset($_GET['dfppub'])){
		 echo '<br>'.$id.'</br>';
		}
		$return = '';
		if ($id && isset($dfp_tags[$id])) {

			$pub = $dfp_tags[$id];
			$return  = "
				<!-- $id -->
				<div class='pub_dfp $sticky $id' id='{$pub['opt_div']}'></div>
				<script type='text/javascript'>\n";

			$js  = " googletag.cmd.push(function() { \n";
			$js  .= " googletag.display('{$pub['opt_div']}'); \n";
			$js  .= "});\n";

			$js  =	apply_filters("dfp_v2_googletag_display", $js, $id, $pub);

			$js  .= " \n site_config_js.dfp_ids = site_config_js.dfp_ids || [] ;
						site_config_js.dfp_ids.push(
							'$id' 
						); ";

			$formats_lazyloading = $this->get_formats_lazyloading();


			if (empty($formats_lazyloading)) {
				$js .= "site_config_js.dfp_refreshed['$id'] = true;";
			} elseif (!empty($formats_lazyloading) && in_array($id, $formats_lazyloading)) {
				$js .= "display_dfp_pub_onscroll('{$pub['opt_div']}', '$id');";
			} else {
				if ($formats_lazyloading) {
					$js .= apply_filters("dfp_v2_refresh_lazyloading", " dfp_show_one_slot('$id' ) ; \n", $id, $pub);
					$js .= "site_config_js.dfp_refreshed['$id'] = true;";
				}

				$this->dfp_pub_tracking($id, isset($pub['condition'])?$pub['condition']:'');

			}

			if (isset($pub['condition'])) {
				$return .= 'if (' . $pub['condition'] . ") { \n" . $js . '}';
			} else {
				$return .= $js;
			}

			$return  .= "</script>";
		}
		return $return;
	}


	function preconnect_dfp()
	{
		global $site_config;
		echo '
		<link rel="preconnect" href="//securepubads.g.doubleclick.net" />
		<link rel="preconnect" href="//www.googletagservices.com" />
		<link rel="preconnect" href="//ad.doubleclick.net" />
		<link rel="preconnect" href="//tpc.googlesyndication.com" />
		<link rel="preconnect" href="//www.gstatic.com" />
		<link rel="preconnect" href="//googleads4.g.doubleclick.net" />
		<link rel="preconnect" href="//pagead2.googlesyndication.com" />
		<link rel="preconnect" href="//googleads.g.doubleclick.net" />
		';
		if ($site_config['ismobile']) {
			echo '<link rel="preconnect" href="//tag.adincube.com" />';
		}
	}


	function get_formats_lazyloading()
	{
		$formats_lazyloading = $this->get_param('formats_lazyloading');
		if (empty($formats_lazyloading)) {
			$formats_lazyloading = [];
		}
		$formats_lazyloading = apply_filters('dfp_formats_lazyloading', $formats_lazyloading);
		return $formats_lazyloading;
	}

	function prefetch_dfp()
	{
		echo '
		<link rel="dns-prefetch" href="//securepubads.g.doubleclick.net" />
		<link rel="dns-prefetch" href="//www.googletagservices.com" />
		<link rel="dns-prefetch" href="//ad.doubleclick.net" />
		<link rel="dns-prefetch" href="//tpc.googlesyndication.com" />
		<link rel="dns-prefetch" href="//www.gstatic.com" />
		<link rel="dns-prefetch" href="//googleads4.g.doubleclick.net" />
		';
	}

	function dfp_pub_tracking($id, $condition)
	{

		add_action('wp_footer', function () use ($id, $condition) {
			//$this->send_ga_dfp_event($id, $condition);
		});
	}

	function send_ga_dfp_event($id, $condition)
	{

		$send_events = '
			jQuery( document ).ready( function(){
				if(!adblock){
					if( site_config_js.manage_tracking_ga){
						var id = "' . $id . '"; 
						if( site_config_js.manage_tracking_ga.indexOf(id) != -1 ){
							setTimeout(function(){ 
								send_GA( "DFP", "' . $id . '", self.location.href);
							}, 3000);
						}
					}else{
						setTimeout(function(){ 
							send_GA( "DFP", "' . $id . '", self.location.href);
						}, 3000);
					}
				}else{
					if( !site_config_js.disable_dfp_adblocker_event ){
						setTimeout(function(){ 
							send_GA( "DFP Adblocker", "' . $id . '", self.location.href);
						}, 3000);
					}
				}
	 		});
		';
		if (isset($condition)) {
			$s = '<script> if (' . $condition . ") { \n" . $send_events . '}</script>';
		} else {
			$s = "<script>" . $send_events . "</script>";
		}
		echo $s;
	}

	function init_mpu_mobile()
	{
		add_action('after_item_1_hp', function () {
			echo $this->get_mpu_mobile();
		});
		add_action('after_item_2_rubrique', function () {
			echo $this->get_mpu_mobile();
		});
		add_filter('the_content', function ($s) {

			global $is_live_content;
			if ($is_live_content) {

				$close_form_position =  strpos($s, '</form');
				$p = (strlen($s) > 22) ?  strpos($s, '</p', 23 + $close_form_position) : 0;

				$mpu_html = $this->get_mpu_mobile();

				if ($p > 0) {
					$s = substr($s, 0, $p) . $mpu_html . substr($s, $p);
				} else {
					$s .= $mpu_html;
				}
			}

			return $s;
		}, 1000);
	}

	function get_mpu_mobile()
	{
		$html = '';
		if (!$this->mpu_mobile_inited) {
			$this->mpu_mobile_inited = true;
			$html = '<div class="clearfix" style="margin: 5px 0;"></div>' . do_shortcode("[dfp_v2 id='mpu_mobile']") . '<div class="clearfix" style="margin: 5px 0;"></div>';
		}
		return $html;
	}


	function insert_pub_dfp_mobile()
	{
		if ($this->count_displayed_mobile_formats < 12) {
			$this->count_displayed_mobile_formats++;
			echo do_shortcode($this->insert_place_dfp_mobile());
			echo  '<div class="displayed-clearfix"></div>';
		}
	}

	function insert_place_dfp_mobile()
	{
		return '[dfp id="mobile_' . $this->count_displayed_mobile_formats . '"]';
	}
}
