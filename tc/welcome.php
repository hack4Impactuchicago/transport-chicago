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


?>


<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
	<title>Read an article</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		table tr th, table tr td {font-size: 12px;}
	</style>
</head>
<body>
<div class="container">
	<h1>Reviews Dashboard</h1>
	<form class='form-inline'>
		<div class="form-group">
			<label for="reviewer_email">Name</label>
			<input name='reviewer_id' type="email" class="form-control" value=<?php echo "'" . $reviewer_name. "'";?> readonly="readonly">
			<label for="reviewer_email">Email address</label>
			<input name='reviewer_id' type="email" class="form-control" value=<?php echo "'" . $reviewer_email. "'";?> readonly="readonly">
		</div>
	</form>
	<h4>You have reviewed <?php echo count($articles_done);?> abstracts.</h4>
	<p>Please try to review at least <strong>15 abstracts/proposals</strong>, not including ones that you've declined to review or have a conflict of interest with...</p>
	<a class="btn btn-success" href="tagging.php" role="button">Read a New Abstract</a>
	<a class="btn btn-danger" href="edit.php" role="button">Edit Submitted Reviews</a>
	<a class="btn btn-primary" href="all_abstracts.php" role="button">Read All Abstracts</a>
	<a class="btn btn-info" href='index.php' role="button">Log Off</a>
	
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
