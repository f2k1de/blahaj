<?php
date_default_timezone_set('Europe/Berlin');

$mastodonsecret = 'ENTER MASTODON SECRET HERE';

$jetzt = date("Y-m-d") . "/" . date("H") . ".xml";
$davor = date("Y-m-d", strtotime("-1 hour")) . "/" . date("H", strtotime("-1 hour")) . ".xml";

$varianten[] = "klein";
$varianten[] = "gross";

for($count = 0; $count < count($varianten); $count++) {

    if(file_exists("blahaj/" . $varianten[$count] . "/" . $jetzt)) {

        if(file_get_contents("blahaj/" . $varianten[$count] . "/" . $jetzt) != "") {


            $xml = simplexml_load_string(file_get_contents("blahaj/" . $varianten[$count] . "/" . $jetzt));
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);


            for($i = 0; $i < count($array['availability']['localStore']); $i++) {
                $jetztarray[$i]['store'] = $array['availability']['localStore'][$i]['@attributes']['buCode'];
                $jetztarray[$i]['stock'] = $array['availability']['localStore'][$i]['stock']['availableStock'];
            }

            if(file_exists("blahaj/" . $varianten[$count] . "/" . $davor)) {
                if(file_get_contents("blahaj/" . $varianten[$count] . "/" . $davor) != "") {

                    $xml = simplexml_load_string(file_get_contents("blahaj/" . $varianten[$count] . "/" . $davor));
                    $json = json_encode($xml);
                    $array = json_decode($json,TRUE);

                    for($i = 0; $i < count($array['availability']['localStore']); $i++) {
                        $davorarray[$i]['store'] = $array['availability']['localStore'][$i]['@attributes']['buCode'];
                        $davorarray[$i]['stock'] = $array['availability']['localStore'][$i]['stock']['availableStock'];
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

    $names = json_decode(file_get_contents("names.json"), true);


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
        "status" => "Es " . $praedikat . " " . $neuehaie[$i]['number'] . " " . $hajwort . " (" . $varianten[$count] . ") in #" . getName($neuehaie[$i]['store'], $names) . " eingezogen.",
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

function getName($nr, $names) {
    for($i = 0; $i < count($names); $i++) {
        if($names[$i]['buCode'] == $nr) {
            return $names[$i]['name'];
        }
    }
}

