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
$query = "SELECT field_id, type FROM Types WHERE problem_id='$pid'";
$results = mysql_query($query);
$types = mysql_fetch_array($results);
print_r($types);
//Validate input
for($case_id = 1;$case_id <= $numcases;$case_id++) { 
    for($field = 1;$field <= $numfields;$field++) {
        echo $_POST["field$case_id-$field"];
    }
    echo $_POST["result$case_id"];
}
//Run cases
system("python $pid.py");
?>