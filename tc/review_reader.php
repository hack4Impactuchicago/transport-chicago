<?php
function read_review_completed($reviewer_email, $submission, $del_list = array()){
	//File where submissions are
	$submisions = 'scores.csv';
	$sub_file = fopen($submisions, 'r');

	// row tracker
	$row = 0;

	// data storage
	$the_data = array();
	$reviewed_keys = array();
	$all_reviews = array();

	// Search through submissions
	while(($sub_csv = fgetcsv($sub_file)) !== FALSE){
		if($row == 0){
			$i = 0;
		}else{
			// Store the CSV output of reviews which match email
			if(trim($reviewer_email) == trim($sub_csv[1]) && in_array(trim($sub_csv[9]), $del_list) == FALSE){
				$the_data[] = array(
					'time' => trim($sub_csv[0]),
					'article_id' => trim($sub_csv[2]),
					'quality' => trim($sub_csv[3]),
					'paramaters' => trim($sub_csv[4]),
					'timely' => trim($sub_csv[5]),
					'writing' => trim($sub_csv[6]),
					'original' => trim($sub_csv[7]),
					'credibility' => trim($sub_csv[8]),
					'score_id' => trim($sub_csv[9])

					);
				$reviewed_keys[] = (int) trim($sub_csv[2]);
			}
			// Store all reviews
			if(in_array(trim($sub_csv[9]), $del_list) == FALSE && trim($sub_csv[3]) != "-1"){
				$all_reviews[] = (int) trim($sub_csv[2]);
			}
		}	
		$row++;
	};

	// Summarize review freuqncy
	$summarized_review_freq = array_count_values($all_reviews);
	$max_review_count = max($summarized_review_freq);

	// pad frequency of article ids by the diff of max and freq of article id
	$article_ids = array();

	

	// Find what articles are left
	foreach($submission as $sub){
		// each id gets one entry
		array_push($article_ids, $sub['id']);
		// additional entries for everything less than max
		for($i = $summarized_review_freq[$sub['id']]; $i < $max_review_count; $i ++){
			array_push($article_ids, $sub['id']);
		}
	};
	// Offsest by one
	// foreach($article_ids as $id){$id++;}
	$whats_left = array_diff($article_ids, $reviewed_keys);

	// Get the next article
	if(count($whats_left) != 0){
		$review_this = $whats_left[array_rand($whats_left)];
	}else{
		$review_this = -1;
	}
	foreach($whats_left as $wl){
		// echo $wl . "<br />";
	}
	// Return review data + next article
	return array($the_data, $review_this);	
}

?>