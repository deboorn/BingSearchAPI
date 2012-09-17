<?php 

	/*
	 * Simple PHP 5 cURL Wrapper for the Bing Search API via Windows Azure Marketplace
	 * @author Daniel Boorn info@rapiddigitalllc.com
	 * @copyright 2012 Daniel Boorn
	 * @bingapidoc https://datamarket.azure.com/dataset/bing/search#schema
	 * @license apache 2.0, code is distributed "as is", use at own risk, all rights reserved
	 */
	class BingSearch{
		
		protected $apiKey = 'your api key here';
		protected $apiRoot = 'https://api.datamarket.azure.com/Bing/Search/v1/';
		
		/*
		 * construct class
		 * @param string $apiKey (optional)
		 * @throws exception with no api key
		 */
		public function BingSearch($apiKey=false){
			if($apiKey) $this->apiKey = $apiKey;
			if($this->apiKey=="") throw new Exception("API Key Required");
		}

		/*
		 * query bing image api
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function queryImage($query){
			return $this->query('Image',$query);
		}
		
		/*
		 * query bing web api
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function queryWeb($query){
			return $this->query('Web',$query);
		}

		/*
		 * query bing video api
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function queryVideo($query){
			return $this->query('Video',$query);
		}
		
		/*
		 * query bing news api
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function queryNews($query){
			return $this->query('News',$query);
		}
		
		/*
		 * query bing released search api
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function queryRelatedSearch($query){
			return $this->query('RelatedSearch',$query);
		}
		
		/*
		 * query bing spelling suggestions api
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function querySpellingSuggestions($query){
			return $this->query('SpellingSuggestions',$query);
		}
		
		/*
		 * query bing api
		 * @param string $type (see api specs)
		 * @param mixed (string or array) $query
		 * @return object
		 */
		public function query($type,$query){
			if(!is_array($query)) $query = array('Query'=>"'{$query}'");
			try{
				return self::getJSON("{$this->apiRoot}{$type}",$query);
			}catch(Exception $e){
				die("<pre>{$e}</pre>");
			}
		}
		
		/*
		 * get json via curl with basic auth
		 * @param string $url
		 * @param array $data
		 * @return object
		 * @throws exception on non-json response (api error)
		 */
		protected function getJSON($url,$data){
			if(!is_array($data)) throw new Exception("Query Data Not Valid. Type Array Required");
			$data['$format'] = 'json';
			$url .= '?' . http_build_query($data);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		    curl_setopt($ch, CURLOPT_USERPWD,  $this->apiKey . ":" . $this->apiKey);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		    $r = curl_exec($ch);
		    $json = json_decode($r);
		    if($json==null) throw new Exception("Bad Response: {$r}\n\n{$url}");
		    return $json;
		}
		
		
	}