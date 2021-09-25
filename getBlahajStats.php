<?php
require "config.php";

if(!is_dir($data_folder . "/klein/" . date("Y-m-d") . "/")) {
    mkdir($data_folder . "/klein/" . date("Y-m-d") . "/", 0777, true);
}
if(!is_dir($data_folder . "/gross/" . date("Y-m-d") . "/")) {
    mkdir($data_folder . "/gross/" . date("Y-m-d") . "/", 0777, true);
}

$args = "--tries=90 --header=\"X-Client-ID:" . $api_key . "\" --user-agent=\"Mozilla/5.0 (Windows NT 6.1; rv:68.0) Gecko/20100101 Firefox/68.0\"";

// --user-agent is not necessary
exec("wget " . $args . " -O " . $data_folder . "/klein/" . date("Y-m-d") . "/" . date("H") . ".json " . $kleinerhai);
exec("wget " . $args . " -O " . $data_folder . "/gross/" . date("Y-m-d") . "/" . date("H") . ".json " . $grosserhai);
?>