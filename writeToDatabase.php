<?php
require "config.php";

$varianten[] = "klein";
$varianten[] = "gross";
$date = date("Y-m-d H") . ":00:00";
$jetzt = date("Y-m-d") . "/" . date("H") . ".xml";

$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);
if ($mysqli->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

for($count = 0; $count < count($varianten); $count++) {

    if(file_exists("blahaj/" . $varianten[$count] . "/" . $jetzt)) {

        if(file_get_contents("blahaj/" . $varianten[$count] . "/" . $jetzt) != "") {


            $json = file_get_contents($data_folder . "/" . $varianten[$count] . "/" . $jetzt);
            $array = json_decode($json, TRUE)['data']; // Extract the data tag


            for($i = 0; $i < count($array); $i++) {
                if(array_key_exists('availableStocks', $array[$i])) {
                    $jetztarray[$i]['store'] = $array[$i]['classUnitKey']['classUnitCode'];
                    $jetztarray[$i]['stock'] = $array[$i]['availableStocks'][0]['quantity'];

                    $sql = "INSERT INTO sharks (id, markt, date, number, size) VALUES (NULL, '" . $jetztarray[$i]['store'] . "', '" . $date . "', '" . $jetztarray[$i]['stock'] . "', '" . $varianten[$count] . "'); ";
                    // $sql = $mysqli->real_escape_string($sql);
                    $mysqli->query($sql);
                }
            }
        }
    }
}
?>