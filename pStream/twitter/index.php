<?php

require_once('src/codebird.php');
//require_once('config.php');

// class which stores basic tweet information
class tInfo{
	public $text;
	public $username;
	public $id;
	public $url;
	public $timez;
	
	public function __construct($text, $uname, $id, $url, $time){
		$this->text = $text;
		$this->username = $uname;
		$this->id = $id;
		$this->url = $url;
		$this->timez = $time;
	}
	
	public function printInfo(){
		echo "Id: " . $this->id . "<br>";
		echo "Username: " . $this->username . "<br>";
		echo "Text: " . $this->text . "<br>";
		echo 'URL: <a href="' . $this->url . '">' . $this->url . '</a>' . "<br>";
		echo "Timestamp: " . $this->timez . "<br>";
		echo "<br>" . "<br>";
	}
}

// set the consumer key ('CONSUMER_KEY', 'CONSUMER_SECRET)
Codebird::setConsumerKey('nhraO9TJhqUVuwrQpO9Q', '1fkkvDGthfK0yZptenVxf1TfNVMzFP9tLieAUGIk');

// get an instance?
$cb = Codebird::getInstance();


session_start();


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
} elseif (!isset($_SESSION['twitter_oauth_verified'])){
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
}
else{
	$cb->setToken($_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
}
?>

<html>
	<head></head>
	<body>
		<?php
			$var = (array)$cb->statuses_homeTimeline('count=10');

			$count=10;
			for ($i = 0; $i < $count; $i = $i+1){
				$id = $var[$i]->id_str;
				$text = $var[$i]->text;
				$username = $var[$i]->user->name;
				$url = "http://www.twitter.com/" . $var[$i]->user->screen_name . "/statuses/" . $id;
				$time = $var[$i]->created_at;
				$tweet = new tInfo($text, $username, $id, $url, $time);
			
				$tweet->printInfo();
			}
		?>
	</body>
</html>
