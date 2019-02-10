<?php
// Start Session
session_start();
$reviewer_email = $_SESSION["reviewer_email"];
$reviewer_name = $_SESSION["reviewer_name"];


if(empty($_SESSION["reviewer_email"])){
	header("Location: http://www.raymondchan.me/tc/");
	die();
}

// Include all required files
include('review_reader.php');
include('read_submissions.php');
include('read_del.php');

// Read Submissions
$all_submissions = read_data();

// Read Del list (deleted reviews)
$del_list = read_del_list();

// Read All Reviews
list($articles_done, $article_id) = read_review_completed($reviewer_email, $all_submissions, $del_list);

function scores_print($reviews, $submissions){
	// Builds HTML
	$html_out = "";

	foreach($reviews as $review){
		// convert time to chicago time
		$chi_time = $review['time'] . " UTC"; 
		$chi_time = date_create_from_format('m/d/Y H:i:s e', $chi_time);
		$chi_time->setTimezone(new DateTimeZone('America/Chicago'));
		$chi_time_print = $chi_time->format('m/d/Y H:i:s T') ;

		$aid = (int) $review['article_id'];
		$html_out .= "<tr>";
		$html_out .= "<td class='time'><small>" . $chi_time_print . "</small></td>";
		$html_out .= "<td class='title'><small>" . $submissions[$aid-2]["title"]  . "</small></td>";
		if(trim($review['quality']) != -1){
			$html_out .= "<td>" . $review['quality'] . "</td>";
			$html_out .= "<td>" . $review['paramaters'] . "</td>";
			$html_out .= "<td>" . $review['timely'] . "</td>";
			$html_out .= "<td>" . $review['writing'] . "</td>";
			$html_out .= "<td>" . $review['original'] . "</td>";
			$html_out .= "<td>" . $review['credibility'] . "</td>";
		}else{
			$html_out .= "<td colspan='6'>You declined to review this abstract.</td>";
		}
		$html_out .= "<td><a href='tagging.php?art_id=" . $aid  . "&sid=". $review['score_id'] ."'>Edit Review</a></td>";
		$html_out .= "</tr>";
	}

	if($html_out == ""){
		$html_out .= "<tr>";
		$html_out .= "<td colspan='9'>You haven't reviewed anything yet. Go <a href='tagging.php'>review</a> some.</td>";
		$html_out .= "</tr>";
	}

	return $html_out;	
}

// Stores table html
$table_html = scores_print($articles_done, $all_submissions);

// Check to see if you've reviewed everything
// Get Score ID if needed for delete list
if ($_GET['done'] == "1") {
	$yay = "<h2>YAY! YOU FINISHED REVIEWING ALL THE ABSTRACTS</h2>";
}else{
	$yay = "";
}

?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
	<title>Edit a Review</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		table tr th, table tr td {font-size: 12px;}
		td {text-align: center;}
		td.time, td.title {text-align: left}
		div.tips {padding: 5px; border-radius: 5px; margin-bottom: 2px;}
	</style>
</head>
<body>
<div style='background-color: #eee; padding: 0 20px'>Hello <?php echo $reviewer_name;?> | <a href='welcome.php'>Reviewer Home</a> | <a href='tagging.php'>Review a Random Abstract</a> | <a href='index.php'>Log Off</a></div>
<div class="container">
	<h1><?php echo $reviewer_name; ?>'s Reviews</h1>
	<div class="form-group">
		<label for="reviewer_email">Email address</label>
		<input name='reviewer_id' type="email" class="form-control" value=<?php echo "'" . $reviewer_email. "'";?> readonly="readonly">
	</div>
	<?php echo $yay; ?>
	<h4>Rating guidelines</h4>
	<ul>
		<li>1: 💩 (happy poo, not so good abstract)</li>
		<li>5: 🌟 (gold star is a good star)</li>
	</ul>
	<div class='bg-warning tips'><p>Tip: Rotate your phone to get maximum width to better see the table</p></div>
	<div class='table-responsive'>
		<table class="table table-striped table-bordered">
		  <tr>
		  	<th class='time'>Review Submitted</th>
		  	<th class='title'>Abstract Title</th>
		  	<th>Overall Score</th>
		  	<th>Within Parameters</th>
		  	<th>Timely &amp; Relevant</th>
		  	<th>Well Written</th>
		  	<th>Original &amp; Innovative</th>
		  	<th>Sound &amp; Credible</th>
		  	<th>Edit?</th>
		  </tr>
		  <?php echo $table_html; ?>
		</table>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
