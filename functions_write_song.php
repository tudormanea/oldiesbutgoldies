<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cristi.coman
 * Date: 25.01.2014
 * Time: 18:42
 * To change this template use File | Settings | File Templates.
 */

include_once ("config.php");


writeSong(1,2,'Petre1', 'Corul Unirii', 'Vocea Unirii', 3, '2014-01-25 21:26:00');

function writeSong($listen_id, $friend_id, $song, $artist, $album, $deezer_id, $timestamp){
    $song_id = matchSong($song, $artist, $album);
    var_export($song_id);
    if ($song_id == 0) {
        $insert = "INSERT INTO songs (song, artist, album, deezer_id) VALUES ('".$song."', '".$artist."', '".$album."', ".$deezer_id.")";
        mysql_query($insert);
        $song_id = mysql_insert_id();
    }
    writeToPlaylist($friend_id, $song_id, $listen_id, $timestamp);
}

function matchSong($song, $artist, $album){
    $sql="
        SELECT s.id
        FROM songs s
        WHERE s.song = '".$song."' AND s.artist = '".$artist."' AND s.album = '".$album."'
    ";
    $result = mysql_query($sql);
    var_export($result);
    while($row = mysql_fetch_array($result))
    {
        return $row['id'];
    }
    return 0;
}

function writeToPlaylist($friend_id, $song_id, $listen_id, $timestamp) {
    $insert = "INSERT INTO playlist (friend_id, song_id, listen_id, date_played)
                VALUES ('".$friend_id."', '".$song_id."', '".$listen_id."', '".$timestamp."')";
    mysql_query($insert);
};
