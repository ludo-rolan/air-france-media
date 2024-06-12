<?php


class WemapPinpoint extends rw_partner
{
	const apiUrl = "https://api.getwemap.com/v3.0/";

	// la liste wemap depend prod / préprod
	public function liste_wemap_convenable()
	{
		if (get_omep_val('wemap_use_api_prod_ids_0672')) {
			$listUrl = (ICL_LANGUAGE_CODE == "fr") ? "52631" : "52629";
		} else {
			$listUrl = "76175";
		}
		return $listUrl;
	}

	// sert à créer un token
	public function creation_de_token()
	{
		$now = new DateTime();
		$wemap_api_info = get_option('wemap_api_info');
		// si isset($wemap_api_info) == false, on met une autre date qu'ajourd'hui (le plus important c'est la diff des secondes)
		// pour qu'on puisse entrer dans la condition (ligne 33) : diff de seconds et générer un token
		$wemap_api_date = isset($wemap_api_info['date']) ? $wemap_api_info['date'] : new DateTime('2022-06-01');
		$diff = $now->diff($wemap_api_date);
		$daysInSecs = abs($diff->format('%r%a') * 24 * 60 * 60);
		$hoursInSecs = abs($diff->h * 60 * 60);
		$minsInSecs = abs($diff->i * 60);
		$seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;

		if ($seconds && $seconds >= 35000) {
			$curl = curl_init();
			$url = self::apiUrl . 'oauth2/token';
			$data = 'client_id=3ul11irCNvoCislGQX5T6X2FjgnWH9jn7qKqc57M&grant_type=client_credentials&client_secret=kPoqInvq03fSHSuTUQrX1Br9dbq9qaFS0XS4kfAk5mZnooN7UHuAnFnCPyoY3egSx7p1mqWDEi4eSf8YyQiDShLk7zQLHeyVsKOMVnk805wVAoAZmEY4daWlp4SePF3w';
			$curl_header = [
				'Authorization: Basic c29tZS1jbGllbnQtaWQ6c29tZS1jbGllbnQtc2VjcmV0',
				'Content-Type: application/x-www-form-urlencoded'
			];
			$curl_option = $this->creation_des_curl_options($url, 'POST', $data, $curl_header);
			curl_setopt_array($curl, $curl_option);

			$response = curl_exec($curl);
			if (!curl_errno($curl)) {
				if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
					$response_json = json_decode($response, true);
					update_option('wemap_api_info', array('token' => $response_json['access_token'], 'date' => new DateTime()));
				}
			}
			curl_close($curl);
		}
		if (isset($wemap_api_info['token'])) {
			return $wemap_api_info['token'];
		} else {
			return false;
		}
	}

	// sert à préparer la requette http
	public function creation_des_curl_options($url, $method_http, $data, $header)
	{
		$array = array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method_http,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => $header,
		);
		return $array;
	}

	// point d'entré
	public function init()
	{
		if (isset($_GET['sync'])) {
			$postId = isset($_GET['post']) ? $_GET['post'] : '';
			$postData = get_post($postId);
			if(get_post_status($postId) == 'publish'){
				$localisationUser = get_post_meta($postId, 'localisation_map', true);
				if ($localisationUser != '') {
					$localisationData = json_decode($localisationUser, true);
				} else {
					$loc = get_post_meta($postId, 'geo_coordonees', true);
					$localisationGeo = explode(',', $loc);
					$localisationData = [
						'latitude' => $localisationGeo[0],
						'longitude' => $localisationGeo[1],
					];
				}
				$wemap_external_id = get_post_meta($postId, 'wemap_external_poi_id', true);
				if (($wemap_external_id == '' || $wemap_external_id == false) && get_post_type($postData)) {
					$data = $this->preparation_data($postData, $localisationData);
					$token = $this->creation_de_token();
					$listUrl = $this->liste_wemap_convenable();
					$this->ajouter_une_nouvelle_pinpoints($token, $data, $postId, $listUrl);
				} else {
					$pinpointID = get_post_meta($postId, 'wemap_external_poi_id', true);
					$data = $this->preparation_data($postData, $localisationData);
					$token = $this->creation_de_token();
					$this->faire_la_maj_de_pinpoint($token, $pinpointID, $data);
				}
			}
		}
	}

	// ajout d'un nouveau pinpoint (include la focntion d'ajout dans la liste)
	public function ajouter_une_nouvelle_pinpoints($token, $data, $postId, $listID)
	{
		$curl = curl_init();
		$url = self::apiUrl . 'pinpoints?access_token=' . $token;
		$curl_header = ['Content-Type: application/json'];

		$curl_option = $this->creation_des_curl_options($url, 'POST', $data, $curl_header);
		curl_setopt_array($curl, $curl_option);
		$response = curl_exec($curl);
		if (!curl_errno($curl)) {
			$curl_infos = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($curl_infos == 201) {
				$response_json = json_decode($response, true);
				$pinpointID = $response_json['id'];
				add_post_meta($postId, 'wemap_external_poi_id', $pinpointID, true);
				$this->ajouter_une_nouvelle_pinpoints_dans_une_liste($token, $listID, $pinpointID);
			}
		}
		curl_close($curl);
	}

	// maj de pinpoint
	public function faire_la_maj_de_pinpoint($token, $pinpointID, $data)
	{
		$curl = curl_init();
		$url = self::apiUrl . 'pinpoints/' . $pinpointID . '?access_token=' . $token;
		$curl_header = ['Content-Type: application/json'];
		$curl_option = $this->creation_des_curl_options($url, 'PUT', $data, $curl_header);
		curl_setopt_array($curl, $curl_option);
		$response = curl_exec($curl);
		if (!curl_errno($curl)) {
			$curl_infos = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		}
		curl_close($curl);
	}

	// sert à préparer la data pour l'ajout et maj
	public function preparation_data($postData, $localisation_info)
	{
		if(gettype($localisation_info) == 'string'){
			$localisation_info = json_decode($localisation_info, true);
		}
		$img_src = wp_get_original_image_path(get_post_thumbnail_id($postData));
		$content = preg_replace ( '/\[carte_addr_(.*?)\]/s' , '' ,  $postData->post_content ) . "<a class='btn btn-wemap btn-addon btn-md pull-right ng-scope' href='" . get_permalink($postData) . "'>Voir l'article</a>";
		$img = file_get_contents($img_src);
		$img64 = base64_encode($img);
		$localisation_latitude = $localisation_info['latitude'];
		$localisation_longitude = $localisation_info['longitude'];
		$data  = [
			"adresse" => $postData->post_title,
			"category" => 1,
			"description" => $content,
			"latitude" => $localisation_latitude,
			"longitude" => $localisation_longitude,
			"media_credits" => 'EnVols',
			"media_file" => [
				"content" => $img64,
				"name" => 'test.png',
				"type" => 'image/png'
			],
			"name" => $postData->post_title,
			"tags" => ['WP']
		];
		$encoded_data = json_encode($data);
		return $encoded_data;
	}

	// sert à ajouter pinpoints dans une liste
	public function ajouter_une_nouvelle_pinpoints_dans_une_liste($token, $listID, $pinpointID)
	{
		$curl = curl_init();
		$url = self::apiUrl . 'lists/' . $listID . '/pinpoints?access_token=' . $token;
		$curl_header = ['Content-Type: application/json'];
		$curl_option = $this->creation_des_curl_options($url, 'POST', '[{"id":' . $pinpointID . '}]', $curl_header);
		curl_setopt_array($curl, $curl_option);
		curl_exec($curl);
		curl_close($curl);
	}
}
