<?php
require "config.php";

$varianten[] = "klein";
$varianten[] = "gross";
$date = date("Y-m-d H") . ":00:00";
$jetzt = date("Y-m-d") . "/" . date("H") . ".json";
$store_names = json_decode(file_get_contents("stores.json"), true);

function get_name($nr, $store_names) {
    for($i = 0; $i < count($store_names); $i++) {
        if($store_names[$i]['id'] == $nr) {
            return $store_names[$i]['name'];
        }
    }
}

for($count = 0; $count < count($varianten); $count++) {

    if(file_exists($data_folder . "/" . $varianten[$count] . "/" . $jetzt)) {

        if(file_get_contents($data_folder . "/" . $varianten[$count] . "/" . $jetzt) != "") {
            print($varianten[$count] . ":\n\n");


            $json = file_get_contents($data_folder . "/" . $varianten[$count] . "/" . $jetzt);
            $array = json_decode($json, TRUE)['data']; // Extract the data tag

            
            $total = 0;
            for($i = 0; $i < count($array); $i++) {
                if(array_key_exists('availableStocks', $array[$i])) {
                    $store = $array[$i]['classUnitKey']['classUnitCode'];
                    $stock = $array[$i]['availableStocks'][0]['quantity'];
                    print(get_name($store, $store_names) . ": " . $stock . "\n");
                    $total += $stock;
                }
            }
            print("∑ " . $total . "\n\n\n");
        }
    }
}
?>