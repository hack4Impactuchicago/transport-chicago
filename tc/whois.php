<?php
session_start();

$reviewer_name = $_POST['reviewer_name'];
$reviewer_email = strtolower($_POST['reviewer_email']);
$auth_phrase = "dora";

//File where submissions are
$submisions = 'MpnfYfEfmY.csv';

$sub_file = fopen($submisions, 'r');

// row tracker
$row = 0;

// data storage
$the_data = array();
$reviewers = array();

// Search through submissions
while(($auth_csv = fgetcsv($sub_file)) !== FALSE){
	if($row == 0){
		// Do Nothing
	}else{
		$the_data[$auth_csv[0]] = $auth_csv[1];
	}
	$row++;
}


fclose($sub_file);

setcookie('reviewer_name', $reviewer_name, time() + 10510000);
setcookie('reviewer_email', $reviewer_email, time() + 10510000);
setcookie('auth_phrase', $auth_phrase, time() + 7200);

$_SESSION["reviewer_email"] = $reviewer_email;
$_SESSION["reviewer_name"] = $reviewer_name;

header( "refresh:5; url=welcome.php" );

if(array_key_exists($reviewer_email, $the_data)){
	header("Location: welcome.php");
	echo "Thanks for checking back in! <br />";
	die();
	
}else{
	$data_store = fopen($submisions, 'a');
	$data = "$reviewer_email\n";
	fwrite($data_store, $data);
	fclose($data_store);

	echo "You have been successfully registered<br />";
}

echo $reviewer_name . "<br />";
echo $reviewer_email . "<br />";

echo "<a href='welcome.php'>Go to the reviewer dashboard</a>"


?>