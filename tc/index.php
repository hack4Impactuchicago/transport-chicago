<?php 

$reviewer_name = $_COOKIE["reviewer_name"];
$reviewer_email= $_COOKIE["reviewer_email"];

$new_user = FALSE;

if(empty($reviewer_name)){
	$inputname = "";
	$new_user = TRUE;
}else{
	$inputname = $reviewer_name;
}

if(empty($reviewer_email)){
	$inputemail = "";
	$new_user = TRUE;
}else{
	$inputemail = $reviewer_email;
}


?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
	<title>Transport Chicago Abstract Review</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
	<h1>Transport Chicago 2018 Abstract Review Site</h1>
	<p>Hello <?php echo $reviewer_name; ?></p>
	<!-- Previous User -->
	<p>Welcome back to the 2018 review site.  To enter, please use the email address you used to register.  If you haven't registered yet, just enter your name and email address and you'll be registered.  I promise that you won't get any automated emails by providing an email.
	<form action='whois.php' method='post'>
		<div class="form-group">
			<label for="reviewer_name">Name</label>
			<input type="Name" class="form-control" name="reviewer_name" placeholder= 'Name' value=<?php echo "'" . $inputname . "'"; ?> >
		</div>
		<div class="form-group">
			<label for="reviewer_email">Email address</label>
			<input type="email" class="form-control" name="reviewer_email" placeholder= 'Email Address' value=<?php echo "'" . $inputemail . "'"; ?> >
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
	</form>

</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
