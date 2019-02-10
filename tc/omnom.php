<?php 
setlocale(LC_TIME, "America/Chicago");

// All the things to save
$r_id = $_POST['reviewer_id'];
$a_id = $_POST['article_id'];
$a_name = $_POST['article_name'];
$abs_q = $_POST['abs_quality'];
$abs_param = $_POST['params'];
$abs_timely = $_POST['timely'];
$abs_writing = $_POST['writing'];
$abs_original = $_POST['original'];
$abs_credible = $_POST['credible'];
$cur_time = strftime("%m/%d/%Y %H:%M:%S");

// Check code to see if this is an edit
$score_id = $_POST['score_id'];

// Logic to fix edits
if($score_id != "-1"){
	$del_list = fopen('del_list.csv', 'a');
	$data = "$score_id\n";
	fwrite($del_list, $data);
	fclose($del_list);
	header( "refresh:5; url=edit.php" );
	$link = "<a href='edit.php'>Go back to edit more</a>";
}else{
	header( "refresh:5; url=tagging.php" );
	$link = "<a href='tagging.php'>Go back to score more</a>";
}

// Storing the correct score_id
$score_id = strftime("%Y%m%d%H%M%S");

// Storing the data
$data_store = fopen('scores.csv', 'a');
$data = "$cur_time, $r_id, $a_id, $abs_q, $abs_param, $abs_timely, $abs_writing, $abs_original, $abs_credible, $score_id \n";
fwrite($data_store, $data);
fclose($data_store);

// Printing out confirmation data
echo "You rated " . addslashes($a_name) . " with: <br />";
echo "Abstract Quality: $abs_q <br />";
echo "Meets Parameters: $abs_param <br />";
echo "Relevant/Timely: $abs_timely <br />";
echo "Writing/Coherent: $abs_writing <br />";
echo "Original/Innovative: $abs_original <br />";
echo "Credible Analysis: $abs_credible <br />";
echo $link;

?>