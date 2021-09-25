<?php
require "config.php";

date_default_timezone_set($timezone);

$jetzt = date("Y-m-d") . "/" . date("H") . ".xml";
$davor = date("Y-m-d", strtotime("-1 hour")) . "/" . date("H", strtotime("-1 hour")) . ".xml";

$varianten[] = "klein";
$varianten[] = "gross";

$store_names = json_decode(file_get_contents("stores.json"), true);

for($count = 0; $count < count($varianten); $count++) {

    if(file_exists("blahaj/" . $varianten[$count] . "/" . $jetzt)) {

        if(file_get_contents("blahaj/" . $varianten[$count] . "/" . $jetzt) != "") {


            $json = file_get_contents($data_folder . "/" . $varianten[$count] . "/" . $jetzt);
            $array = json_decode($json, TRUE)['data']; // Extract the data tag

            for($i = 0; $i < count($array); $i++) {
                if(array_key_exists('availableStocks', $array[$i])) {
                    $jetztarray[$i]['store'] = $array[$i]['classUnitKey']['classUnitCode'];
                    $jetztarray[$i]['stock'] = $array[$i]['availableStocks'][0]['quantity'];
                }
            }

            if(file_exists("blahaj/" . $varianten[$count] . "/" . $davor)) {
                if(file_get_contents("blahaj/" . $varianten[$count] . "/" . $davor) != "") {

                    $json = file_get_contents($data_folder . "/" . $varianten[$count] . "/" . $davor);
                    $array = json_decode($json, TRUE)['data']; // Extract the data tag
        
                    for($i = 0; $i < count($array); $i++) {
                        if(array_key_exists('availableStocks', $array[$i])) {
                            $jetztarray[$i]['store'] = $array[$i]['classUnitKey']['classUnitCode'];
                            $jetztarray[$i]['stock'] = $array[$i]['availableStocks'][0]['quantity'];
                        }
                    }
                }
            }
        }
    }

    $neuehaie = array();
    for($i = 0; $i < count($davorarray); $i++) {
        if(isset($jetztarray[$i])) {
            if($jetztarray[$i]['store'] == $davorarray[$i]['store']) {
                // Selber Store
                if($jetztarray[$i]['stock'] > $davorarray[$i]['stock']) {
                    // Neuer Blahaj
                    $neuehaie[] = ['number' => ($jetztarray[$i]['stock'] - $davorarray[$i]['stock']), 'store' => $jetztarray[$i]['store']];
                }
            }
        }
    }


    for($i = 0; $i < count($neuehaie); $i++) {
        $headers = [
            'Authorization: Bearer ' . $mastodonsecret
          ];

        if ($neuehaie[$i]['number'] == 1) {
            $praedikat = "ist";
            $hajwort = "neues Blåhaj";
        } else {
            $praedikat = "sind";
            $hajwort = "neue Blåhajar";
        }
        $status_data = array(
        "status" => "Es " . $praedikat . " " . $neuehaie[$i]['number'] . " " . $hajwort . " (" . $varianten[$count] . ") in #" . get_name($neuehaie[$i]['store'], $store_names) . " eingezogen.",
        "language" => "deu",
        "visibility" => "public"
        );

        $ch_status = curl_init();
        curl_setopt($ch_status, CURLOPT_URL, "https://botsin.space/api/v1/statuses");
        curl_setopt($ch_status, CURLOPT_POST, 1);
        curl_setopt($ch_status, CURLOPT_POSTFIELDS, $status_data);
        curl_setopt($ch_status, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_status, CURLOPT_HTTPHEADER, $headers);

        $output_status = json_decode(curl_exec($ch_status));

        curl_close ($ch_status);
        // Do not DDOS Mastodon
        sleep(1);
    }
}

function get_name($nr, $store_names) {
    for($i = 0; $i < count($store_names); $i++) {
        if($store_names[$i]['id'] == $nr) {
            return $store_names[$i]['name'];
        }
    }
}