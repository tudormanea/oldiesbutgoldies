<?php
require_once("facebook-php-sdk/src/facebook.php");

$fbconfig['baseUrl'] = "https://54.201.135.223/oldiesbutgoldies"; // "http://thinkdiff.net/demo/newfbconnect1/iframe/sdk3";
$fbconfig['appBaseUrl'] = "https://apps.facebook.com/socialmusictop"; // "http://apps.facebook.com/thinkdiffdemo";

$loginConfig = array(
    'scope' => 'user_about_me,user_location,user_friends,user_actions.music,friends_about_me,friends_location,friends_actions.music'
);

if (isset($_GET['code'])) {
    header("Location: " . $fbconfig['appBaseUrl']);
    die();
}

$config = array(
    'appId' => '405884936212776',
    'secret' => '31161e23724fce0344b96aa7dc8d2c6f',
    'fileUpload' => false, // optional
    'allowSignedRequest' => true, // optional, but should be set to false for non-canvas apps
);

$facebook = new Facebook($config);
$user_id = $facebook->getUser();
if ($user_id) {
    // We have a user ID, so probably a logged in user.
    // If not, we'll get an exception, which we handle below.
    try {
        $user_profile = $facebook->api('/me', 'GET');
    } catch (FacebookApiException $e) {
        // If the user is logged out, you can have a
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl($loginConfig);
        echo("<script> top.location.href='" . $login_url . "'</script>");
        error_log($e->getType());
        error_log($e->getMessage());
        die();
    }
} else {
    // No user, print a link for the user to login
    $login_url = $facebook->getLoginUrl($loginConfig);
    echo("<script> top.location.href='" . $login_url . "'</script>");
    die();
}
