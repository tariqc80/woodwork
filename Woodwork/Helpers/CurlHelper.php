<?php 
class CurlHelper {

	public static function sendCurl( $url )
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// Quick fix to accept unverified certs for testing purposes.
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($curl);
		curl_close($curl);

		return $result;
	}	

}