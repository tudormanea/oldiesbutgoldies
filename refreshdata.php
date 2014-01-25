<?php
require_once("fblogin.php");
require_once("functions_write_song.php");
$listens = array();
$myListens = $facebook->api("/me/music.listens?limit=100");
$listens = $myListens['data'];
$friends = $facebook->api("/me/friends");
foreach ($friends['data'] as $friend) {
    $friendListens = $facebook->api("/" . $friend['id'] . "/music.listens?limit=100");
    $listens = array_merge($listens, $friendListens['data']);
}

$formattedListens = array();
foreach ($listens as $listen) {
    $formattedListen = array(
        'listen_id' => $listen['id'],
        'friend_id' => $listen['from']['id'],
        'timestamp' => $listen['start_time']
    );
    $objTimestamp = new DateTime($listen['start_time'], new DateTimeZone("UTC"));
    $formattedListen['timestamp'] = $objTimestamp->format("Y-m-d H:i:s");
    $listenDetails = $facebook->api("/" . $listen['data']['song']['id']);
    $formattedListen['artist'] = $listenDetails['data']['musician'][0]['name'];
    $formattedListen['song'] = $listenDetails['title'];
    $formattedListen['album'] = $listenDetails['data']['album'][0]['url']['title'];
    $audioUrl = $listenDetails['audio'][0]['url'];
    if (strpos($audioUrl, "http://www.deezer.com/track/") !== false) {
        $formattedListen['deezer_id'] = substr($audioUrl, 28);
    } else {
        $formattedListen['deezer_id'] = '';
    }
    writeSong($formattedListen['listen_id'], $formattedListen['friend_id'], $formattedListen['song'], $formattedListen['artist'], $formattedListen['album'], $formattedListen['deezer_id'], $formattedListen['timestamp']);
    $formattedListens[] = $formattedListen;
}
