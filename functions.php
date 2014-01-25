<?php

function facebookLogin()
{
    $app_id = "405884936212776";
    $canvas_page = "https://54.201.135.223/oldiesbutgoldies/";
    $auth_url = "http://www.facebook.com/dialog/oauth?client_id="
        . $app_id . "&redirect_uri=" . urlencode($canvas_page);

    $redirectToFacebook = true;
    if (isset($_REQUEST["signed_request"])) {
        $signed_request = $_REQUEST["signed_request"];
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        if (!empty($data["user_id"])) {
            return $data['user_id'];
        }
    } else {
        print_r($_REQUEST);
        die();
    }
    echo("<script> top.location.href='" . $auth_url . "'</script>");
    die();
}

function searchDeezerSong($query){


    echo $url = "http://api.deezer.com/search?q=".urlencode($query);

    $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    echo $error_no = curl_errno($ch);
    curl_close($ch);
    print_r($data);exit;
    $results = json_decode($response);
    if(!empty($results)){
        return $results[0];
    }
    return false;
}
