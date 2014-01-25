<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cristi.coman
 * Date: 25.01.2014
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */
//error_reporting(E_ALL); ini_set('display_errors',1);
define('DSN_USER', 'fb');
define('DSN_PASS', 'fb');
define('DSN_HOST', '54.201.135.223');
define('DSN_DB', 'oldiesbutgoldies');
define('DSN_TOPMUSIC', 'mysql://' . DSN_USER . ':' . DSN_PASS . '@' . DSN_HOST . '/' . DSN_DB);

if (!function_exists('connector_app')) {
    function connector_app() {
        $db = mysql_connect(DSN_HOST, DSN_USER, DSN_PASS);
        if (!$db) {
            die('Could not connect: ' . mysql_error());
        } else {
             mysql_select_db(DSN_DB, $db);
        }
        return $db;
    }
}
connector_app();
