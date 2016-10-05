<?php 
ob_start();
session_start();
	require_once '../../../includes/functions.php';
	require_once('src/facebook.php');

    // constructs a facebook object consisting of parse user information. i don't know
    // if this is needed anymore but I dont want to delete it just yet
	class fbInfo{
		public $username;
		public $text;
		public $timestamp;
		public $url;
		public $media;
	
		public function __construct($username, $text, $time, $url, $media){
			$this->username = $username;
			$this->text = $text;
			$this->timestamp = $time;
			$this->url = $url;
			$this->media = $media;
		}
	
		/*public function printInfo(){
			echo "Username: " . $this->username . "<br>";
			echo "Text: " . $this->text . "<br>";
			echo "Time: " . $this->timestamp . "<br>";
			echo 'URL: <a href="' . $this->url . '">' . $this->url . "</a><br>";
			echo "Type: " . $this->media . "<br>";
			echo "<br>" . "<br>";
		}*/
	}
    
    // info about the app. might put the consumer key and consumer secret in a "config.php" file later
	$id=$_SESSION['id'];
	$con=dbCon();
	$app_id = '146761882162735';
	$app_secret = '9499690c2b4bf4a99247632a105fe721';
	$my_url = 'http://ec2-54-214-14-238.us-west-2.compute.amazonaws.com/pStream/facebook/sdk';
	
	// if this is empty, that means that the user hasn't gone to facebook to authenticate our website
	$code = $_REQUEST['code'];
		if (empty($code)){
			// redirect to login dialog
			$_SESSION['fbook_state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
			$dialog_url = "https://wwww.facebook.com/dialog/oauth?client_id="
				. $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
				. $_SESSION['fbook_state'] . "&scope=read_stream";
			
			// redirect user to facebook authentication page
			header("Location: " . $dialog_url);
			die();
		}
	
		if ($_SESSION['fbook_state'] && ($_SESSION['fbook_state'] === $_REQUEST['state'])){
			// state variable matches
			// craft an access url to trade the code for an access token
			$token_url = "https://graph.facebook.com/oauth/access_token?"
				. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
				. "&client_secret=" . $app_secret . "&code=" . $code;
		
		    // a json file containing the access token and refresh token
			$response = file_get_contents($token_url);
			$params = null;
			parse_str($response, $params);
			
			// store the access token in the session.
			$_SESSION['fbook_access_token'] = $params['access_token'];
			
			if($con) {
			    // store the token in the DB for the user. i dont think we need to store fbook_state
			    // because thats just a unique one-time MD5 hash. i'll ask ian about it later.
			    // i THINK its for storing a refresh token, but fbook doesn't require one.
				setFacebookInfo($con,$id,$_SESSION['fbook_access_token'],$_SESSION['fbook_state']);
			}
			header('Location: ../../../index.php');
			//session_write_close();
		} else{
			// possible CSRF attempt
			header('Location: ../../../account.php');
		}
		
			/*
	If people decide to decline to authorize your app in the Login Dialog, they'll be redirected to:

	YOUR_REDIRECT_URI?
    error_reason=user_denied
   &error=access_denied
   &error_description=The+user+denied+your+request.
   &state=YOUR_STATE_VALUE
   */
?>
