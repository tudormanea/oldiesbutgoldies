<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cristi.coman
 * Date: 25.01.2014
 * Time: 15:51
 * To change this template use File | Settings | File Templates.
 */
include_once("facebook-php-sdk/config.php");

echo "test2";
$sql = "SELECT mail FROM users WHERE id = 1";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result))
{
    var_export($row);
    echo "<br>";
}


