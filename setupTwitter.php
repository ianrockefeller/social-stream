<?php session_start();
/*
setupTwitter.php:
this file runs the oauth verifier for when
a user wants to allow our application to 
stream their twitter feed.
Then sets the session variables if not yet set
and updates the user's record. 
*/
require_once 'pStream/twitter/src/codebird.php';
require_once 'includes/functions.php';

$id=$_SESSION['id'];
Codebird::setConsumerKey('nhraO9TJhqUVuwrQpO9Q', '1fkkvDGthfK0yZptenVxf1TfNVMzFP9tLieAUGIk');
$cb = Codebird::getInstance();
$con=dbCon();

if (!isset($_GET['oauth_verifier'])){
	// gets a request token
	$reply = $cb->oauth_requestToken(array(
		'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
	));
	
	// stores it
	$cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
	$_SESSION['twitter_oauth_token'] = $reply->oauth_token;
	$_SESSION['twitter_oauth_token_secret'] = $reply->oauth_token_secret;
	
	// gets the authrize screen URL
	$auth_url = $cb->oauth_authorize();

	header('Location: ' . $auth_url);
	die();
}  else {//not sure if needed
	$cb->setToken($_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
	$reply = $cb->oauth_accessToken(array(
		'oauth_verifier' => $_GET['oauth_verifier']
	));
	// store the authenticated token, which may be different from the request token (!)
	$_SESSION['twitter_oauth_token'] = $reply->oauth_token;
	$_SESSION['twitter_oauth_token_secret'] = $reply->oauth_token_secret;
	
	$cb->setToken($_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
	//header('Location: index.php');
	if($con) {
		setTwitterInfo($con,$id,$_SESSION['twitter_oauth_token'],$_SESSION['twitter_oauth_token_secret']);
	} else {
		echo 'database connection error';
	}
	header('Location: index.php');
}

/*
elseif (!isset($_SESSION['twitter_oauth_verified'])){
	// gets the access token
	$cb->setToken($_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
	$reply = $cb->oauth_accessToken(array(
		'oauth_verifier' => $_GET['oauth_verifier']
	));
	// store the authenticated token, which may be different from the request token (!)
	$_SESSION['twitter_oauth_token'] = $reply->oauth_token;
	$_SESSION['twitter_oauth_token_secret'] = $reply->oauth_token_secret;
	
	$cb->setToken($_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
	$_SESSION['twitter_oauth_verified'] = true;

	//header('Location: account.php');

}
*/
?>
