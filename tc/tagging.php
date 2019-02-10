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

// Read Submissions
$all_submissions = read_data();

// Read All Reviews
list($articles_done, $article_id) = read_review_completed($reviewer_email, $all_submissions);


// Set article title in GET if needed or redirect to edits page if done.
if (empty($_GET['art_id']) === FALSE) {
	$article_id = (int) $_GET['art_id'];
}else if($article_id == -1){
	// Redirect to edits page
	header("Location: edit.php?done=1");;
}

// Last Scores
$l_score = array();

// Get Score ID if needed for delete list
if (empty($_GET['sid']) == FALSE) {
	$sid = $_GET['sid'];
	// Insert code here to prefill in past edits
	foreach($articles_done as $review){
		if($review['score_id'] == $sid){
			$l_score['abs_quality'] = $review['quality'];
			$l_score['params'] = $review['paramaters'];
			$l_score['timely'] = $review['timely'];
			$l_score['writing'] = $review['writing'];
			$l_score['original'] = $review['original'];
			$l_score['credible'] = $review['credibility'];
			$l_score['new'] = 0;
		}
	}

}else{
	$sid = -1;
	$l_score['new'] = 1;
}

// Score writing loop
function scoring_loop($max_score, $question, $id_name, $prior_scores){
	// find out what the score was before
	if($l_score['new'] == 0 && $prior_scores['abs_quality'] != '-1'){
		$old_score = (int) $prior_scores[$id_name];
	}else{
		$old_score = -5;
	}

	// Top of the form control
	$scoring_html .= '<div class="form-group">';
	$scoring_html .= "<label for='$id_name'>$question</label><br />";

	// The loop
	for($i = 0; $i < $max_score; $i++){
		// Emoji Setting
		switch($i){
			case 0:
				$emoji = '(💩)';
				break;
			case $max_score - 1:
				$emoji = '(🌟)';
				break;
			default:
				$emoji = '';
		}
		// Fix score so that it's not keyed to zero
		$score = $i + 1;

		// Check for old scores
		if($old_score == $score){
			$checked = 'checked';
		}else{
			$checked = '';
		}

		// Actual Text to print
		$scoring_html .= "<label class='radio-inline'><input type='radio' name='$id_name' value='$score' $checked >" . "$score $emoji" . '</label>';
	}

	// Add for conflict of interest
	if($id_name == "abs_quality"){
		if($prior_scores['abs_quality'] == '-1'){$ck = 'checked';}else{$ck = '';}
		$scoring_html .= "<br /><br /><label class='radio-inline'><input type='radio' name='abs_quality' value='-1' $ck > <strong>I should not review this (conflict of interest, etc.)</strong></label>";
	}

	// Bottom of the Form control
	$scoring_html .= '</div>';	

	// Return String
	return $scoring_html;
}

// Scoring Categories
$score_categories = array(
	'params' => 'How does this abstract meets parameters?',
	'timely' => 'Is this abstract relevant &amp; timely?',
	'writing' => 'Is the writing clear &amp; concise?',
	'original' => 'Is this abstract original &amp; innovative?',
	'credible' => 'Does this abstract have a sound and credible analysis?',
	'abs_quality' => 'Should we give this abstract a presentation (overall score)?'
	);


$all_scoring_html = '';
foreach($score_categories as $cat => $q){
	$all_scoring_html .= scoring_loop(5, $q, $cat, $l_score);	
}

// get all article ids and make link to page in a dropdown
$dropdown = "<h4>Go to an article:</h4><select class='form-control' onchange='location = this.options[this.selectedIndex].value;'>";
foreach($all_submissions as $sub){
	$aid = (int) $sub['id'];
	if($aid == $article_id){
		$chosen_one = $sub;
	}
	$title = $sub['title'];
	$dropdown .= "<option value='tagging.php?art_id=$aid'>Article ID $aid &mdash; $title</option>"; 
}
$dropdown .= "</select>";

?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
	<title>Abstract Review</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div style='background-color: #eee; padding: 0 20px'>Hello <?php echo $reviewer_name;?> | <a href='welcome.php'>Reviewer Home</a> | <a href='edit.php'>Edit a Submitted Review</a> | <a href='index.php'>Log Off</a></div>
<div class="container">
	<h1>Abstract Review</h1>
	<h2>Guidelines</h2>
	<div class="bg-info" style="padding: 5px;">
		<p>The abstract reviews are being conducted double blind, though authors will be unmasked during the committee meeting.  Things to consider:
		<ul>
			<li><strong>Parameters</strong>: Does it meet the overal spirit of Transport Chicago?</li>
			<li><strong>Timeliness</strong>: Is the topic of new or state of the art practice?</li>
			<li><strong>Writing</strong>: Is the abstract well written, including grammar and wordiness?</li>
			<li><strong>Originality</strong>: Is this article new?  Have you seen it before?</li>
			<li><strong>Credibility</strong>: Is the author credible? Do they provide enough supporting evidence?</li>
			<li><strong>Conflicts of Interest</strong>: Can you clearly unmask the author based on your work?  Is it a conflict of interest, such as a close co-worker or in a direct report/supervisor? If so, please note it at the bottom, no need to answer the other questions</li>
		</ul>
		Remember, we'll be discussing all the abstracts during the committee meeting.
		</p>
	</div>

	<h2><?php echo $chosen_one['title'];?><br />
		<small>
			<strong>
				<?php 
					//echo "xxxxxxxxx"; 
					echo $chosen_one['author'];
				?></strong> &mdash;
			<em><?php echo $chosen_one['affiliation'];?></em>
		</small>
	</h2>
	<!-- <h3>Article number: <?//php echo $chosen_one['id'];?></h3> -->
	<h3>Presentation Type: <?php echo $chosen_one['pres_type'];?></h3>
	<p><?php echo $chosen_one['abstract'];?></p>
	<h3>Learning Objectives</h3>
	<ol>
		<li><?php echo $chosen_one['learn1'];?></li>
		<li><?php echo $chosen_one['learn2'];?></li>
	</ol>
	<h3>Application to Professional Practice</h3>
	<p><?php echo $chosen_one['application'];?></p>
<hr />
<h4>Rating guidelines</h4>
<ul>
	<li>💩 (happy poo, not so good abstract)</li>
	<li>🌟 (gold star is a good star)</li>
</ul>
<form action='omnom.php' method='post'>
	<div class="form-group">
		<label for="reviewer_email">Email address</label>
		<input name='reviewer_id' type="email" class="form-control" value=<?php echo "'" . $reviewer_email. "'";?> readonly="readonly">
	</div>	
	<input name='article_id' type="hidden" value=<?php echo "'" . $chosen_one['id']. "'";?> >
	<input name='article_name' type="hidden" value=<?php echo "'" . $chosen_one['title'] . "'";?> >
	<input name='score_id' type="hidden" value=<?php echo "'" . $sid . "'";?> >
	
	<? echo $all_scoring_html; ?>
	
  	<button type="submit" class="btn btn-lg">Submit</button>
</form>
<br />

<?php echo $dropdown;?>
</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
