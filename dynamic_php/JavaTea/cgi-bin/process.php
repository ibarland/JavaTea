<?php

include '../init.php';

$pid = $_POST['pid'];
$numfields = $_POST['numfields'];
$numcases = $_POST['numcases'];
//Check if user is logged in
if(is_authed()) {
    //Log attempt if user is logged in
    log_attempt($_SESSION['username'], $pid);
    $attempt_id = mysql_insert_id();
    for($case_id = 1;$case_id <= $numcases;$case_id++) { 
        for($field = 1;$field <= $numfields;$field++) {
            log_input($attempt_id, $case_id, $field, $_POST["field$case_id-$field"]);
        }
        log_output($attempt_id, $case_id, $_POST["result$case_id"]);
    }
}
//Build XML String for processing
$xml = "";
$xml .= escapeshellcmd("<testCases>");
for($case_id = 1;$case_id <= $numcases;$case_id++) { 
    $xml .= escapeshellcmd("<testCase>");
    for($field = 1;$field <= $numfields;$field++) {
        $xml .= escapeshellcmd("<argument><type>")."int".escapeshellcmd("</type><value>").$_POST["field$case_id-$field"].escapeshellcmd("</value></argument>");
    }
    $xml .= escapeshellcmd("<return><type>int</type>");
    $xml .= escapeshellcmd("<value>").$_POST["result$case_id"].escapeshellcmd("</value>");
    $xml .= escapeshellcmd("</return>");
    $xml .= escapeshellcmd("</testCase>");
}
$xml .= escapeshellcmd("</testCases>");
//pass XML to java to verify input
$output = shell_exec("python parse.py $pid $xml");
echo $output;
?>
