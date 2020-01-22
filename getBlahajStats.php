<?php
$kleinerhai = "https://www.ikea.com/de/de/iows/catalog/availability/50455234/";
$grosserhai = "https://www.ikea.com/de/de/iows/catalog/availability/30373588/";


if(!is_dir("blahaj/klein/" . date("Y-m-d") . "/")) {
    mkdir("blahaj/klein/" . date("Y-m-d") . "/", 0777, true);
}
if(!is_dir("blahaj/gross/" . date("Y-m-d") . "/")) {
    mkdir("blahaj/gross/" . date("Y-m-d") . "/", 0777, true);
}

exec("wget --tries=90 --user-agent=\"Mozilla/5.0 (Windows NT 6.1; rv:68.0) Gecko/20100101 Firefox/68.0\" -O blahaj/klein/" . date("Y-m-d") . "/" . date("H") . ".xml " . $kleinerhai);
exec("wget --tries=90 --user-agent=\"Mozilla/5.0 (Windows NT 6.1; rv:68.0) Gecko/20100101 Firefox/68.0\" -O blahaj/gross/" . date("Y-m-d") . "/" . date("H") . ".xml " . $grosserhai);


