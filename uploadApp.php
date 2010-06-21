<?php
include('header.inc');

/*
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__). 'err.txt');
error_reporting(E_ALL);
*/


echo("Hello.")

if ($_FILES['file']['error'] > 0) {
	echo "<p>" ; 
	echo "Sorry the file failed to upload.<br>";
	echo "Please email your file to ibarland@radford.edu.<br>";
	echo "</p>" ; 
}
elseif (strlen($_POST['last']) < 1) {
	echo "<p>" ; 
	echo "Please supply the last name of the applicant!<br>"; 
	echo "You can use the back button on  your browser to go back<br>"; 
	echo "</p>" ; 
} 
elseif ($_FILES["file"]["size"] /1024 > 1000) {
	echo "<p>" ; 
	echo "Sorry! the file you are trying to upload is too large.<br>"; 
	echo "Please check that you are only uploading the application form <br>";
	echo "       or a reference letter. <br> Thank you. ";
	echo "</p>" ; 
}
else {
	$dirName = $_POST['last']; 
	$firstInitial = $_POST['first'].substr(0); 
	$myDir = "applications/".$dirName.$firstInitial."/";
	$targetPath = $myDir.$_FILES['file'] ['name'].microtime(); 
	echo "Saving to ".$targetPath." disabled.";
	/*
	mkdir($myDir); 
	ini_set('upload_tmp_dir','/home/sstem/dynamic_php/');
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
		 echo "<center><p>File ".$_FILES['file'] ['name']." successfully uploaded!\n</center></p>"; 
	} else {
		echo "Sorry the file failed to upload.<br>";
		echo "Please email your file to ibarland@radford.edu.<br>";
	} 
        */

}

include('footer.inc');
?>
