<?php
$probId = $_POST['probId'];
$output = "";
foreach($_POST as $i) {
	$output = $output."%1B".'"'.$i.'"';
}
system("java $probId $output");
?>
