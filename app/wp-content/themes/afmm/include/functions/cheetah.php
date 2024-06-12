<?php
/**
* Cheetah Digital - API
*/

class Cheetah{
	private static $_instance;

	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new Cheetah();
		}
		return self::$_instance;
	}

	public function __construct(){
	}

	/**
	 * Récupération du token pour s’authentifier
	 * @return [String] access_token
	 */
	public function get_token(){
		$response = wp_remote_post(
			"https://api.ccmp.eu/services2/authorization/oAuth2/Token",
			array(
				'method' => 'POST',
				'timeout' => 15,
				'headers' => array(),
				'body' => array(
					'grant_type' => 'password',
					'username' => 'Mjg1Mzc6MTA0MQ==',
					'password' =>"4b7276263e74414f98b8b30920b920ec"
				),
			)
		);
		if(is_wp_error( $response )){
			return null;
		}else {
			if($response['response']['code'] == 200){
				$body = json_decode($response['body'], true);
				$access_token = $body['access_token'];
				return $access_token;
			}
		}
		return null;
	}

	/**
	 * Vérification si contact existant et abonné
	 * @param  [String] $email : Email du contact
	 * @return [String] Si le contact existe ou pas
	 */
	public function check_contact($email){
		$access_token = $this->get_token();
		if($access_token != null){
			global $site_config;
			$response = wp_remote_get(
				"https://d.ccmp.eu/Fr/1041/1/?email=$email&p=6k2qhh6KtFB7H2iQ8vD9exfb3iKcZTM5g253zZM9p24QHDdxn4F48a2zPiLU7uP332CU",
				array(
					'method' => 'GET',
					'timeout'=> 15,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => array(),
				)
			);
			
			if(!empty($response['response']) && $response['response']){
				if($response['body'] == "non"){
					return "NOT_EXIST";
				}else{
					return "EXIST";
				}
			}
			return "Error Checking if existing contact";
		}
		return "Erreur Token";
	}
	/**
	 * function : bridge_cheetah_for_partners
	 */
	function check_exist_email(){
		$result = array(
			'error' => true,
			'message' => 'missing/wrong token or missing mail .'
		);
		$token = (isset($_GET['token']) && $_GET['token'] == 'AZppA45PAc4872AKCzeaPk134KEz') ? $_GET['token'] : '';
		$email = (isset($_GET['email'])) ? $_GET['email'] : '';
		if( empty($token) || empty($email) ){
			return json_encode($result);
		}
		//rw_send_cache_headers(60);//1 min
		$response = $this->check_contact($email);
		
		$result = array(
			'error' => false,
			'message' => $response
		);
		if($response == "Error Checking if existing contact"){
			$result = array(
				'error' => true,
				'message' => $response." . Please contact your service provider !"
			);
		}elseif($response == "Erreur Token"){
			$result = array(
				'error' => true,
				'message' => $response." . Please contact your service provider !"
			);
		}
		return json_encode($result);
	}
	
/**
	 * Paramètres à placer dans le body
	 * @param  [Array] $fields les champs contact
	 * @param  [int] $api_post_id  api post id
	 * @param  [int] $value_optin 
	 * @return [String] les paramètres body
	 */
	public function get_info_update_contact($fields, $api_post_id, $value_optin, $src_nl){
		$access_token = $this->get_token();
		global $site_config;
		//initialize values
		$part_value="";//part value 
		$edito_value="";//edito Value 
		$date_optin_part = "" ; //date_optin_part
		$date_optin_edito = "" ; //envoyée si edito cochée
		$source_nl_edito = "";

		$new_config_nl_cheetah = true;

		$checked_part = false;
		$checked_edito = true;
		$checked_edito_box = false;
		$category_optin_value ="";

		$new_user = true;

		if($api_post_id == 23){
				//OLD USER
				$category_optin_value = 1;
				$new_user = false;
		}elseif($api_post_id == 0){
				//NEW USER
				$category_optin_value = 99;
		}

		$cheetah_nl = isset($site_config['cheetah_nl']) ? $site_config['cheetah_nl'] : false ;
		if( !$cheetah_nl )
			return array();
		
		//prefix from site config
		$prefix = isset($cheetah_nl['prefix']) ? $cheetah_nl['prefix'] : '';
		//acronym from site config
		$acronym = isset($cheetah_nl['acronym']) ? $cheetah_nl['acronym'] : '';
		// optins containes the checkboxes each present element means it has been checked edito/part
		$optins = isset($fields['optins']) ? $fields['optins'] : array();
		$optins_categories = isset($fields['optins_categories']) ? $fields['optins_categories'] : array();
		if(!empty($cheetah_nl['disable_unbounce_src'])){
			if(isset($optins['optin_part']) && $optins['optin_part']=='checked'){
					$checked_part = true;			
			}
		}else{
			foreach ($optins as $optin) {
				if(strpos($optin,'optin_edito')!== false){
					$checked_edito_box=true;
				}elseif(strpos($optin,'optin_part')!== false){
					$checked_part = true;			
				}
			}
		}
		if($src_nl == 'NL' && !$checked_edito_box){
			$checked_edito = false;
		}elseif($src_nl== 'inread'){
			//to keep functionnal the existing inread sent data checked <=> optin part
			$checked = isset($fields['checked']) ? $fields['checked'] : 'false';
			//convert to boolean
			$checked_part = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
		}
		if($checked_part){
			date_default_timezone_set("Europe/Paris");
			$date_optin_part= date("Y-m-d H:i");
			$current_blog_id = get_current_blog_id();
			 if($src_nl != 'NL' && isset($current_blog_id) &&get_current_blog_id() !=5) //MF inscription NL
				$checked_edito = true; //TO SUBSCRIBE TO PARTENAIRES MEANS YOU VE ALREADY SUBSCRIBED TO EDITO [except MF NL]
			//WHO CHECKED PART OPTIN
			if($api_post_id == 23){
				//OLD USER
				$part_value = 1;
			}
			elseif($api_post_id == 0 || $api_post_id == 30){
				//NEW USER
				$part_value = 1;
			}	
		}
		if($checked_edito){
			//WHO CHECKED PART edito
			$date_optin_edito = date("Y-m-d H:i");
			if($src_nl == 'NL'){
				$source_nl_edito =  isset($cheetah_nl['src_nl']) ? $cheetah_nl['src_nl'] : $src_nl;
			}elseif($src_nl == 'inread'){
				$source_nl_edito =  isset($cheetah_nl['src_nl_inread']) ? $cheetah_nl['src_nl_inread'] : $src_nl;
			}


			if($api_post_id == 23){
				//OLD USER
				$edito_value = 1;
			}
			elseif($api_post_id == 0 || $api_post_id == 30){
				//NEW USER
				$edito_value = 1;
			}	
		}
		

		$conf_optins = isset($cheetah_nl['optin']) ? $cheetah_nl['optin'] : array();

		$lang = isset($fields['lang']) ? $fields['lang'] : '';
		if($api_post_id == 0){
			if( !empty($lang) && isset($cheetah_nl['apiPostIds_by_lang']) && isset($cheetah_nl['apiPostIds_by_lang'][$lang]) ){
				$api_post_id = $cheetah_nl['apiPostIds_by_lang'][$lang];
			}else if( isset( $cheetah_nl['apiPostId']) ){
				$api_post_id =  $cheetah_nl['apiPostId'];
			}
		}


		if($src_nl == "UNBOUNCE"){
			$source_nl_edito = $src_nl;
			if (!$cheetah_nl['disable_unbounce_src']){
				$part_value = '';
				$api_post_id = isset(	$cheetah_nl['apiPostId_unbounce']) ? $cheetah_nl['apiPostId_unbounce'] :  56 ;
				$edito_value='1';
			}
		}
		if ($src_nl == 'Poool') {
		 	$source_nl_edito = $src_nl;
		 	$part_value = 1;
		 	$date_optin_part = date("Y-m-d H:i");
		}
		$optin_part_key_suffix = apply_filters('optin_part_cheetah_suffix','part');
		$date_optin_part_key_suffix = apply_filters('date_optin_part_cheetah_suffix','part');
		$param_body_array = array(
			'apiPostId'=> $api_post_id,
			'data' => array(
				array(
					'name' => 'email',
					'value' =>$fields['email']
				)
			)
		);
		if($src_nl == "NL" || $src_nl == "Poool"){
			//news letter
			$source_partenaires = 'NL';
			if ($src_nl == 'Poool') {
			 	$param_body_array['data'][] = array('name'  => 'source_site', 'value' => 'Inscription_poool');
				$source_partenaires = 'Poool';
			}

			$param_body_array['data'][] = array('name'  => 'nom', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'prenom', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'civilité', 'value' => "");
			if( isset($fields['date_de_naissance']) && !empty($fields['date_de_naissance']) ){
				$param_body_array['data'][] = array('name'  => 'date_de_naissance', 'value' => $fields['date_de_naissance']);
			}
			$param_body_array['data'][] = array('name'  => 'adresse', 'value' => "");
			if( isset($fields['code_postal']) && !empty($fields['code_postal']) ){
				$param_body_array['data'][] = array('name'  => 'code postal', 'value' => $fields['code_postal']);
			}
			$param_body_array['data'][] = array('name'  => 'ville', 'value' =>"");
			if( isset($fields['country']) && !empty($fields['country']) ){
				$param_body_array['data'][] = array('name'  => 'pays', 'value' => $fields['country']);
			}
			$param_body_array['data'][] = array('name'  => 'telephone', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'email_md5', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'email_sha256', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'date_creation', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'proprietaire', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'profession', 'value' => "");
			if( isset($fields['langue']) && !empty($fields['langue']) ){
				$param_body_array['data'][] = array('name'  => 'langue', 'value' => $fields['langue']);
			}
			$param_body_array['data'][] = array('name'  => $new_config_nl_cheetah ? 'optin_edito' : $prefix.'_optin_edito', 'value' => $edito_value);
			$param_body_array['data'][] = array('name'  => $new_config_nl_cheetah ? 'date_optin_edito' : 'date_optin_'.$acronym.'_edito', 'value' => $date_optin_edito);
			$param_body_array['data'][] = array('name'  => $new_config_nl_cheetah ? 'source_edito' : 'source_'.$acronym.'_edito', 'value' => $source_nl_edito);
			$param_body_array['data'][] = array('name'  => 'source_site', 'value' => 'Site');
			$param_body_array['data'][] = array('name'  => $new_config_nl_cheetah ? 'optin_partenaires' : $acronym.'_optin_'.$optin_part_key_suffix, 'value' => $part_value);
			$param_body_array['data'][] = array('name'  => $new_config_nl_cheetah ? 'date_optin_partenaires' : 'date_optin_'.$acronym.'_'.$date_optin_part_key_suffix, 'value' => $date_optin_part);
			
			
			
			if( isset($fields['secteur']) && !empty($fields['secteur']) ){
				$param_body_array['data'][] = array('name'  => 'secteur', 'value' => $fields['secteur']);
			}
			if( isset($fields['profession']) && !empty($fields['profession']) ){
				$param_body_array['data'][] = array('name'  => 'profession', 'value' => $fields['profession']);
			}


			if( $cheetah_nl['add_outpout_params'] ){
				$param_body_array['data'][] = array('name'  => 'date_optout_'.$acronym.'_edito', 'value' => $date_optin_edito);
				$param_body_array['data'][] = array('name'  => 'raison_optout_'.$acronym.'_edito', 'value' => 'texte');
				$param_body_array['data'][] = array('name'  => 'source_'.$prefix.'_part', 'value' => $src_nl);
				$param_body_array['data'][] = array('name'  => 'date_optout_'.$acronym.'_part', 'value' => $date_optin_part);
				$param_body_array['data'][] = array('name'  => 'raison_optout_'.$acronym.'_part', 'value' => 'texte');
			}

			if($new_config_nl_cheetah){
				$site_name = isset($cheetah_nl['categorie_cheetah']) ? $cheetah_nl['categorie_cheetah'] : htmlspecialchars_decode( get_bloginfo() );
				$param_body_array['data'][] = array('name'  => 'categorie', 'value' => $site_name);
				$param_body_array = apply_filters('cheetah_param_body_array', $param_body_array, count($optins_categories));
				$param_body_array['data'][] = array('name'  => 'source_partenaires', 'value' =>  $part_value ? $source_partenaires : '');
				$param_body_array['apiPostId'] = '';

				if($new_user && isset($cheetah_nl['apiPostId'])){
					$param_body_array['apiPostId'] = $cheetah_nl['apiPostId'];
				}else{
					$param_body_array['apiPostId'] = 161;
				}
			}

		}
		if($src_nl == "UNBOUNCE"){
			if( isset($fields['civilite']) && !empty($fields['civilite'])){
				$civilite ='';
				if ($fields['civilite']=='Mr'){
					$civilite ='1';
				}
				else $civilite ='2';
				$param_body_array['data'][] = array('name'  => 'civilite', 'value' => $civilite);
			}
			if( isset($fields['nom']) && !empty($fields['nom']) ){
				$param_body_array['data'][] = array('name'  => 'nom', 'value' => $fields['nom']);
			}
			if( isset($fields['prenom']) && !empty($fields['prenom']) ){
				$param_body_array['data'][] = array('name'  => 'prenom', 'value' => $fields['prenom']);
			}
		}
		if($src_nl == "premium"){
			if( isset($fields['email']) && !empty($fields['email']) ){
				$param_body_array['data'][] = array('name'  => 'email', 'value' => $fields['email']);
			}
		}
		if( !empty($lang) && isset($cheetah_nl['tme_lang']) && isset($cheetah_nl['tme_lang'][$lang]) ){
			$param_body_array['data'][] = array('name'  => $cheetah_nl['tme_lang'][$lang], 'value' => 1);
		}
		//TO CONFIRM
		$optins=$optins_categories;
		$nbr_optins = count($optins);
		for ($i=0; $i < $nbr_optins; $i++) {
			
			$param_body_array['data'][] = array('name'  => $optins[$i], 'value' => $category_optin_value);
			
			if(!empty($conf_optins[$optins[$i]]['date_opt']))
				$param_body_array['data'][] = array('name'  => $conf_optins[$optins[$i]]['date_opt'], 'value' => date('Y-m-d H:i'));
			if($conf_optins[$optins[$i]]['src_opt'])
				$param_body_array['data'][] = array('name'  => $conf_optins[$optins[$i]]['src_opt'], 'value' => $src_nl);
		}
		$info = array(
			'method' => 'POST',
			'timeout'=> 15,
			'headers' => array(
				'authorization' => 'Bearer '.$access_token,
				'accept' => 'application/json',
				'content-type' => 'application/json'
			),
			'body' => json_encode($param_body_array)
		);
		return $info;
	}

	/**
	 * Insertion ou Modification d'un contact
	 * @param  [Array] $fields les champs contact
	 * @return  [String] 
	 */
	public function update_contact($fields, $src_nl){
		global $site_config;
		$check = $this->check_contact($fields['email']);
		$apiPostId =  isset($site_config['cheetah_nl']['apiPostId']) ? $site_config['cheetah_nl']['apiPostId'] : 0;
		if($check == "NOT_EXIST"){
				$response = wp_remote_post(
					"https://api.ccmp.eu/services2/api/Recipients",
					$this->get_info_update_contact($fields, 0, 99, $src_nl)
				);		
			if($response['response']['code'] == 200)
				return "NEW_USER";
		}else if($check == "EXIST"){
				$response = wp_remote_post(
					"https://api.ccmp.eu/services2/api/Recipients",
					$this->get_info_update_contact($fields, 23, 1, $src_nl)
				);	
			if($response['response']['code'] == 200)
				return "UPDATE_USER";
		}
		return $check;
	}
}

global $cheetah;
$cheetah =  new Cheetah();


// Actions
add_action('init', 'update_user_chetaah_ajax');

// Filters 
add_filter('default_option_rewrite_rules',  'create_rewrite_rules_cheetah');
add_filter('option_rewrite_rules', 'create_rewrite_rules_cheetah');


function update_user_chetaah_ajax(){
	if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'update_user_chetaah_ajax') ){
		if (isset($_POST['src_nl']) && $_POST['src_nl'] === 'Poool') {
			check_ajax_referer( 'poool_cheetah', 'nonce_poool' );
		}
		global $cheetah;
		$fields = isset($_POST['fields']) ? $_POST['fields'] : '';
		$src_nl = isset($_POST['src_nl']) ? $_POST['src_nl'] : '';
		if( $fields && $src_nl ){
			echo json_encode($cheetah->update_contact($fields, $src_nl));
		}
		echo false;
		exit();
	}
}

/**
 * Création de l'URL personnalisée pour API
 * @param  [Array] $rewrite
 * @return [Array]
 */
function create_rewrite_rules_cheetah($rewrite) {
	global $wp_rewrite;
	$new_rules = array( 'api\/crm$' => 'index.php?category_name=crm_api01' );
	if(!is_admin() && is_array($rewrite) ){
 		$rewrite = $new_rules + $rewrite;
	}
	return $rewrite;
}