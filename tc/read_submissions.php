<?php
function read_data(){
	//File where submissions are
	$submisions = 'submission_data.csv';

	// Title of target names
	$author_name = 'Name';
	$title_name = 'Proposal Title';
	$abstract_name = 'Abstract (limit 250 words)';
	$affil_name = 'Affiliation';
	$id_name = 'id';
	$keyword_name = "Keywords, separated by a semicolon (;)";
	$LO1_name = "Learning Objective 1";
	$LO2_name = "Learning Objective 2";
	$pro_app_name = "What information and insights will audience members be able to apply in professional practice?";
	$pres_name = "Preferred Presentation Format:";

	$ban = array();


	$sub_file = fopen($submisions, 'r');

	// row tracker
	$row = 0;

	// data storage
	$the_data = array();

	// Search through submissions
	while(($sub_csv = fgetcsv($sub_file)) !== FALSE){
		if($row == 0){
			for($field = 0; $field < count($sub_csv); $field++){
				switch($sub_csv[$field]){
					case $author_name:
						$field_num = $field;
						break;
					case $id_name:
						$id_num = $field;
						break;
					case $title_name:
						$title_num = $field;
						break;
					case $abstract_name:
						$abstract_num = $field;
						break;
					case $affil_name:
						$affil_num = $field;
						break;
					case $LO1_name:
						$LO1_num = $field;
						break;
					case $LO2_name:
						$LO2_num = $field;
						break;
					case $pro_app_name:
						$pro_app_num = $field;
						break;
					case $pres_name:
						$pres_num = $field;
						break;
				}
			}
		}else{
			if(in_array($row - 1, $ban) == FALSE){
				$the_data[] = array(
					"id" => $sub_csv[$id_num], 
					"author" => $sub_csv[$field_num],
					"title" => $sub_csv[$title_num],
					"abstract" => $sub_csv[$abstract_num],
					"affiliation" => $sub_csv[$affil_num],
					"learn1" => $sub_csv[$LO1_num],
					"learn2" => $sub_csv[$LO2_num],
					"pres_type" => $sub_csv[$pres_num],
					"application" => $sub_csv[$pro_app_num]
					);
			}
		}
		$row++;
	};
	return $the_data;	
}

?>