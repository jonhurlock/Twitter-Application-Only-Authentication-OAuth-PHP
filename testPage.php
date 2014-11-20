<?php

// lib
require_once('Oauth.php');

// Obtain bearer token
if (isset($_POST['getBearerToken'])) {
	
	$tokenResult = get_bearer_token();
}

// invalidate/revoke bearer token
if (isset($_POST['invalidateBearerToken'])) {
	
	$token = get_bearer_token();
	$invalidate_result = invalidate_bearer_token($token);
}

// test bearer token with a search
if (isset($_POST['testBearerToken'])) {	

	$query = $_POST['search'];
	$token = get_bearer_token();
	// search request
	$results = search_for_a_term($token, $query, 'mixed', 5);
	// Put response into separate array, display below.
	// This site is helpful for examining JSON: http://jsonviewer.stack.hu
	// (Use twitter api example json, since function returns php associative array)
	$tweets = $results['statuses'] ;
}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>App-only Oauth</title>
</head>

<body>
<h1>App-only Oauth</h1>
<p>Get, revoke or test an application bearer token for application-only requests to Twitter API.</p>
<hr>
<h3>Get token</h3>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="submit" name="getBearerToken" value="Get Bearer Token">
</form>
<?php if ($tokenResult) {
	
	if (is_array($tokenResult)) {
		echo "Error: <br>\n";
		print_r($tokenResult);
	} else {
		echo "Token: ".$tokenResult;
	}
} ?>
<hr>
<h3>Revoke token</h3>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="submit" name="invalidateBearerToken" value="Invalidate Bearer Token">
</form>
<?php if ($invalidate_result) print_r($invalidate_result); ?>
<hr>
<h3>Test token</h3>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<label>Search:</label><br>
    <input type="text" name="search" value="jonhurlock"><br>
    <input type="submit" name="testBearerToken" value="Test Bearer Token">
</form>
<?php if ($tweets) {
	echo "<ul>\n";
	foreach ($tweets as $tweet) {
		echo "<li>\n";
		echo "\t<h4>".$tweet['user']['screen_name'].":</h4>\n";
		echo "\t<p>".$tweet['text']."</p>\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
} ?>
</body>
</html>