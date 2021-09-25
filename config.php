<?php
$data_folder = "blahaj"; // Where all the data is saved to
$stock_url = "https://api.ingka.ikea.com/cia/availabilities/ru/de?itemNos=";
$kleinerhai = $stock_url . "50455234";
$grosserhai = $stock_url . "30373588";
$api_key = "b6c117e5-ae61-4ef5-b4cc-e0b1e37f0631"; // This seems to be constant

$mysql_host = "localhost";
$mysql_username = "grafana";
$mysql_password = "PASSWORD";
$mysql_database = "grafana_blahaj";

$mastodonsecret = 'ENTER MASTODON SECRET HERE';
$timezone = 'Europe/Berlin';
?>