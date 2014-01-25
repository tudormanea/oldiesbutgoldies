<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cristi.coman
 * Date: 25.01.2014
 * Time: 20:09
 * To change this template use File | Settings | File Templates.
 */

include_once ("config.php");

function top_query ($query_type, $user_id, $city_id, $friend_id, $limit) {

    $where = "";
    if (!$limit) $limit = 5;
    switch($query_type) {
        case 'Daily':
            $where = " AND DATE(p.date_played) >= DATEDIFF(CURDATE, -1) ";
            break;
        case 'Weekly':
            $where = " AND DATE(p.date_played) >= DATEDIFF(CURDATE, -6) ";
            break;
        case 'City':
            $where = " AND c.id = '".$city_id."' ";
            break;
        case 'Friend':
            $where = " AND f.id = '".$friend_id."' ";
            break;
    }

    $sql = "
        SELECT
          COUNT(s.id) AS times,
          s.song,
          s.deezer_id
        FROM playlist p
        LEFT JOIN songs s ON p.song_id = s.id
        LEFT JOIN friends f ON p.friend_id = f.id
        LEFT JOIN cities c ON f.city_id = c.id
        LEFT JOIN users u ON f.user_id = u.id
        WHERE
            1=1
          ".$where."
        GROUP BY s.id
        ORDER BY times DESC
        ".$limit."
    ";
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result))
    {
        var_export($row);
    }
}
