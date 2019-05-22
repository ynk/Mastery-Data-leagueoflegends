<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>

<center>

<form action="" method="POST">

    Username: <input type="text"  maxlength="16" name="name" id="name">
    <select name="server_select" id="server_select">
        <option value="na1">North America</option>
        <option value="euw1">EU West</option>
        <option value="eun1">Eu North</option>
        <option value="ru">Russia</option>
        <option value="kr">Korean</option>
        <option value="br1">Brazil</option>
        <option value="jp1">Japan</option>
        <option value="tr1">tr1</option>
        <option value="la1">la1</option>
        <option value="la2">la2</option>
    </select>
    <input type="submit" value="submit" name="submit_btn">
</form>
</center>
<body>
<?php

require 'config/config.php';
require 'config/allChampionByID.php';
if(isset($_REQUEST['submit_btn']))
{
    $name = $_POST["name"];
    $server = $_POST["server_select"];
    $url = "https://".$server.".api.riotgames.com/lol/summoner/v4/summoners/by-name/".$name."?api_key=".$api_key;


    $contents = file_get_contents($url);
    $jsonSum = json_decode($contents, true);

    if($contents !== false){

        $sumprofileIconId = $jsonSum["profileIconId"];
        $sumName = $jsonSum["name"];
        $sumPuuid = $jsonSum["puuid"];
        $sumLevel= $jsonSum["summonerLevel"];
        $sumAccountId = $jsonSum["accountId"];
        $sumId = $jsonSum["id"];
        $sumRevisionDate= $jsonSum["revisionDate"];



        $masteryURL = "https://".$server.".api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/".$sumId."?api_key=".$api_key;

        $masteryContent = file_get_contents($masteryURL);
        $jsonMastery = json_decode($masteryContent, true);
        if($masteryContent !== false){

            echo "<table class=tablemastery><tr><thead><th>Picture</th> <th>Name</th><th>Mastery Score</th><th>Mastery Level</th><th>Last Played</th></tr></thead>";

            $i = 0;
            while($i < count($jsonMastery)){ //
                $mil = $jsonMastery[$i]["lastPlayTime"];
                $seconds = $mil / 1000;
                echo "<tr>";
                echo "<td>"."<img src=https://opgg-static.akamaized.net/images/lol/champion/".getChampionIDToname($jsonMastery[$i]["championId"]).".png?image=w_32&v=1>".  "</td>";
                echo "<td>".getChampionIDToname($jsonMastery[$i]["championId"])."</td>";
                echo "<td>".number_format($jsonMastery[$i]["championPoints"])."</td>";
                echo "<td>".$jsonMastery[$i]["championLevel"]."</td>";
                echo "<td>".date("d-m-Y", $seconds)."</td>";
                echo "</tr>";
                $i++;
            }
            echo "</table>";

        }


    }
    else{
        echo "<pre>An error has occurred!</pre>";
    }



}


?>

</body>
</html>
