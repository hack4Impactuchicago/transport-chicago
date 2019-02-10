<?php
function read_del_list(){
	//File where submissions are
	$submisions = 'del_list.csv';
	$sub_file = fopen($submisions, 'r');

	// row tracker
	$row = 0;

	// data storage
	$the_data = array();

	// Search through submissions
	while(($sub_csv = fgetcsv($sub_file)) !== FALSE){
		if($row == 0){
			$i = 0;
		}else{
			// Store the CSV output of the del list
			array_push($the_data, trim($sub_csv[0]));
		}	
		$row++;
	};

	// Return review data + next article
	return $the_data;	
}

?>