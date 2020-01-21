<?php
$varianten[] = "klein";
$varianten[] = "gross";
$date = date("Y-m-d H") . ":00:00";
$jetzt = date("Y-m-d") . "/" . date("H") . ".xml";

$mysqli = new mysqli("localhost", "grafana", "PASSWORD", "grafana_blahaj");
if ($mysqli->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

for($count = 0; $count < count($varianten); $count++) {

    if(file_exists("blahaj/" . $varianten[$count] . "/" . $jetzt)) {

        if(file_get_contents("blahaj/" . $varianten[$count] . "/" . $jetzt) != "") {


            $xml = simplexml_load_string(file_get_contents("blahaj/" . $varianten[$count] . "/" . $jetzt));
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);


            for($i = 0; $i < count($array['availability']['localStore']); $i++) {
                $jetztarray[$i]['store'] = $array['availability']['localStore'][$i]['@attributes']['buCode'];
                $jetztarray[$i]['stock'] = $array['availability']['localStore'][$i]['stock']['availableStock'];

                $sql = "INSERT INTO sharks (id, markt, date, number, size) VALUES (NULL, '" . $jetztarray[$i]['store'] . "', '" . $date . "', '" . $jetztarray[$i]['stock'] . "', '" . $varianten[$count] . "'); ";
//                $sql = $mysqli->real_escape_string($sql);
                $mysqli->query($sql);
            }
        }
    }
}

