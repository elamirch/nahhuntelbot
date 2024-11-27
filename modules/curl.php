<?php

function curl(string $url, string $data = null, array $header = null, string $request = null) {
    //Session initialization
    $curl_session = curl_init($url);
    curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
    
    //Adding header if existed
    if(!is_null($header)) {
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $header);
    }

    //Adding data as postfields if existed
    if(!is_null($data)) {
        curl_setopt($curl_session, CURLOPT_POST, true);
        curl_setopt($curl_session, CURLOPT_POSTFIELDS, $data);
    }

    //Using proxy if set in .env file
    global $USE_PROXY;
    if($USE_PROXY){
        curl_setopt($curl_session, CURLOPT_PROXY, '127.0.0.1');
        curl_setopt($curl_session, CURLOPT_PROXYPORT, 2081);
    }

    //Setting custom request if existed
    if($request == "DELETE") {
        curl_setopt($curl_session, CURLOPT_CUSTOMREQUEST, "DELETE");
    }

    //Executing the cURL request
    $response = curl_exec($curl_session);

    //Checking for errors
    if (curl_errno($curl_session)) {
        throw new Exception('Curl error: ' . curl_error($curl_session));
    } else {
        //Print response from the server
        return $response;
    }
}
