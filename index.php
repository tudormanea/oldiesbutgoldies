<?php
$start = time();
require_once("fblogin.php");
$listens = array();
$myListens = $facebook->api("/me/music.listens?limit=100");
$listens = $myListens['data'];
/*
$friends = $facebook->api("/me/friends");
foreach ($friends['data'] as $friend) {
    $friendListens = $facebook->api("/" . $friend['id'] . "/music.listens?limit=100");
    $listens = array_merge($listens, $friendListens['data']);
}
*/

$formattedListens = array();
foreach ($listens as $listen) {
    $formattedListen = array(
        'listen_id' => $listen['id'],
        'friend_id' => $listen['from']['id']
    );
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
    $formattedListens[] = $formattedListen;
}

$delta = time() - $start;

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

    <script type="text/javascript" src="https://cdns-files.deezer.com/js/min/dz.js"></script>
    <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <style type="text/css">
        .progressbarplay {
            cursor: pointer;
            overflow: hidden;
            height: 8px;
            margin-bottom: 8px;
            background-color: #F7F7F7;
            background-image: -moz-linear-gradient(top, whiteSmoke, #F9F9F9);
            background-image: -ms-linear-gradient(top, whiteSmoke, #F9F9F9);
            background-image: -webkit-gradient(linear, 0 0, 0 100%, from(whiteSmoke), to(#F9F9F9));
            background-image: -webkit-linear-gradient(top, whiteSmoke, #F9F9F9);
            background-image: -o-linear-gradient(top, whiteSmoke, #F9F9F9);
            background-image: linear-gradient(top, whiteSmoke, #F9F9F9);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5f5f5', endColorstr='#f9f9f9', GradientType=0);
            -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
        }

        .progressbarplay .bar {
            cursor: pointer;
            background: #4496C6;
            width: 0;
            height: 8px;
            color: white;
            font-size: 12px;
            text-align: center;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
            -moz-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-transition: width .6s ease;
            -moz-transition: width .6s ease;
            -ms-transition: width .6s ease;
            -o-transition: width .6s ease;
            transition: width .6s ease;
        }

        .scroll {
            height: 400px !important;
            overflow: scroll;
        }

        .white, .white a {
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Social Music Top</h1>

    <h2>Welcome <?php echo $user_profile['first_name'] . " " . $user_profile['last_name']; ?>
        from <?php echo $user_profile['location']['name']; ?></h2>

    <div class="row marketing">
        <div class="col-lg-12 ">
            <div id="dz-root"></div>
            <div id="player" style="width:100%;" align="center"></div>

            <script>
                $(document).ready(function () {
                    $.ajax({
                        url: 'refreshdata.php'
                    });
                    $("#controlers input").attr('disabled', true);
                    $("#slider_seek").click(function (evt, arg) {
                        var left = evt.offsetX;
                        DZ.player.seek((evt.offsetX / $(this).width()) * 100);
                    });
                });

                function onPlayerLoaded() {
                    $("#controlers input").attr('disabled', false);
                }
                DZ.init({
                    appId: '131231',
                    channelUrl: 'https://developers.deezer.com/examples/channel.php',
                    player: {
                        container: 'player',
                        cover: true,
                        playlist: true,
                        width: 700,
                        height: 80,
                        onload: onPlayerLoaded
                    }
                });
            </script>
        </div>
    </div>
</div>
<div class="row marketing">
    <div class="col-lg-12 scroll">
        <table>
            <?php
            echo "Data read time: " . $delta . "<br/>";
            foreach ($formattedListens as $formattedListen) {
                echo '<tr>';
                echo '<td>' . $formattedListen['artist'] . " - " . $formattedListen['song'] . '</td>';
                if ($formattedListen['deezer_id'] <> '') {
                    echo '<td><button type="button" class="btn btn-primary btn-xs" onclick="DZ.player.playTracks([' . $formattedListen["deezer_id"] . ']); return false;"><span class="glyphicon glyphicon-play white"></span></button></td>';
                }
                echo '</tr>';
                //echo json_encode($formattedListen)."<br/><br/>";
            }
            /*
            echo "Listens: ".count($listens)."<br/>";
            foreach ($listens as $listen) {
                echo $listen['id']." - ".$listen['data']['song']['title']." (".$listen['from']['name'].")<br/>";
            }

            echo "Friends: ".count($friends['data'])."<br/>";
            foreach ($friends['data'] as $friend) {
                echo $friend['name']." ".$friend['id']."<br/>";
            }
            */
            ?>
        </table>
    </div>
</div>
</body>
</html>

