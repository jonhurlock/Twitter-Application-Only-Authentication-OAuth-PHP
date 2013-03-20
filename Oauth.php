<?php
/**
** 	TwitterAppAuth.py
*
*	Created by Jon Hurlock on 2013-03-20.
* 	
*	Jon Hurlock's Twitter Application-only Authentication App by Jon Hurlock (@jonhurlock)
*	is licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License.
*	Permissions beyond the scope of this license may be available at http://www.jonhurlock.com/.
*/


// fill in your consumer key and consumer secret below
define('CONSUMER_KEY', 'enter_your_consumer_key_here');
define('CONSUMER_SECRET', 'enter_your_consumer_secret_here');

	/**
	*	Get the Bearer Token, this is an implementation of steps 1&2
	*	from https://dev.twitter.com/docs/auth/application-only-auth
	*/
	function get_bearer_token(){
		// Step 1
		// step 1.1 - url encode the consumer_key and consumer_secret in accordance with RFC 1738
		$encoded_consumer_key = urlencode(CONSUMER_KEY);
		$encoded_consumer_secret = urlencode(CONSUMER_SECRET);
		// step 1.2 - concatinate encoded consumer, a colon character and the encoded consumer secret
		$bearer_token = $encoded_consumer_key.':'.$encoded_consumer_secret;
		// step 1.3 - base64-encode bearer token
		$base64_encoded_bearer_token = base64_encode($bearer_token);
		// step 2
		$url = "https://api.twitter.com/oauth2/token"; // url to send data to for authentication
		$headers = array( 
			"POST /oauth2/token HTTP/1.1", 
			"Host: api.twitter.com", 
			"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
			//"Authorization: Basic ".$base64_encoded_bearer_token."",
			"Authorization: Basic ".$base64_encoded_bearer_token."",
			"Content-Type: application/x-www-form-urlencoded;charset=UTF-8", 
			"Content-Length: 29"
		); 
	
		$ch = curl_init();  // setup a curl
		curl_setopt($ch, CURLOPT_URL,$url);  // set url to send to
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
		curl_setopt($ch, CURLOPT_POST, 1); // send as post
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); // post body/fields to be sent
		$header = curl_setopt($ch, CURLOPT_HEADER, 1); // send custom headers
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		ob_start();  // start ouput buffering
		curl_exec ($ch); // execute the curl
		$retrievedhtml = ob_get_contents(); // grab the retreived html
		ob_end_clean(); //End buffering and clean output 
		curl_close($ch); // close the curl
		$output = explode("\n", $retrievedhtml);
		$bearer_token = '';
		
		foreach($output as $line)
		{
			if($pos === false)
			{
			}else{
				$bearer_token = $line;
			}
		}
		
		$bearer_token = json_decode($bearer_token);
		return $bearer_token->{'access_token'};
	}
	
	/**
	* Search
	* Basic Search of the Search API
	*/
	function search_for_a_term($query){
		$url = "https://api.twitter.com/1.1/search/tweets.json"; // base url
		$q = $query; // query term

    	$formed_url ='?q='.$q; // fully formed url
    	$headers = array( 
    		"GET /1.1/search/tweets.json".$formed_url." HTTP/1.1", 
    		"Host: api.twitter.com", 
			"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
    		"Authorization: Bearer ".get_bearer_token()."",
    	);
		$ch = curl_init();  // setup a curl
		curl_setopt($ch, CURLOPT_URL,$url.$formed_url);  // set url to send to
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
		ob_start();  // start ouput buffering
		$output = curl_exec ($ch); // execute the curl
		$retrievedhtml = ob_get_contents(); // grab the retreived html
		ob_end_clean(); //End buffering and clean output 
		curl_close($ch); // close the curl
		return $retrievedhtml;
	}

	// lets run a search.
	print search_for_a_term("test");
	?>