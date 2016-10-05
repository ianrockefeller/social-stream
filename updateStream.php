<?php
/*
updateStream.php
main stream background process
TODO: huge update - break up this function into two functions
                    one that stores info into the DB
                    one that outputs a streamobject from the DB to the screen
                    
      that way we can use cronjob to get a huge amount of data at a time
      separately from when we output the information. this will result in
      much much less API calls being used because we only have a limited number.
      
      since_id, max_id - these can be used to tell the websites exactly
      which posts we need so we dont need to process duplicate posts.
*/
require_once 'includes/functions.php';
require_once 'pStream/twitter/src/codebird.php';//twitter
require_once 'pStream/facebook/sdk/src/facebook.php';//facebooki

// reddit includes
require_once 'pStream/reddit/Client.php';
require_once 'pStream/reddit/GrantType/IGrantType.php';
require_once 'pStream/reddit/GrantType/AuthorizationCode.php';

//twitter info class
//possibly used for all objects
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

$con= dbCon();
if($con) {
	$str = "select user_id, twitter, tpw, facebook, fbpw, reddit from user";//sql string
	$result = $con->query($str);//query string
	if($result->num_rows>0) {//if got results
		while($r=$result->fetch_assoc()) {//for each user
			//twitter
			if($r['twitter'] && $r['tpw']) {
				runTwitter($con,$r['user_id'],$r['twitter'],$r['tpw']);
			}
			//facebook
			if($r['facebook']) {
				runFacebook($con,$r['user_id'],$r['facebook'],$r['fbpw']);
			}
			if ($r['reddit']){
			    runReddit($con, $r['user_id'], $r['reddit']);
			}
		}
	} else {//no records returned
		echo 'records return error';
	}
}
?>

<?php 

function runTwitter($con,$id,$token,$secret) {
// set the consumer key ('CONSUMER_KEY', 'CONSUMER_SECRET)
Codebird::setConsumerKey('nhraO9TJhqUVuwrQpO9Q', '1fkkvDGthfK0yZptenVxf1TfNVMzFP9tLieAUGIk');
//print_r($token);
//print_r($secret);
$cb = Codebird::getInstance();//build an empty object
$cb->setToken($token, $secret);//set token

$count=5;
$var = (array)$cb->statuses_homeTimeline('count=10');
//print_r($var);
$a=array();//array of streamobject's

foreach($var as $v){
if($v->id_str!=null && $v->text!=null && $v->user->name!=null
	&& $v->user->screen_name!=null && $v->created_at!=null && $v!=null) {
	$pid = $v->id_str;
	$text = $v->text;
	$username = $v->user->name;
	$url = "http://www.twitter.com/" . $v->user->screen_name . "/statuses/" . $pid;
	$time = $v->created_at;
	//$tweet = new tInfo($text, $username, $pid, $url, $time);
	//$tweet->printInfo();
	$a = new StreamObject($pid,$url,$time,$text," ",$username,"twitter");
	/*
	$a= new StreamObject();
	$a->id=$pid;
	$a->url=$url;
	$a->time=$time;
	$a->body=$text;
	$a->title="";
	$a->sender=$username;
	$a->type="twitter";
	*/
	
	if(!empty($a)) {
		prepareObject($con,$a,$id);
	}
}		
}
}
?>

<?php 
function runFacebook($con,$id,$token,$secret) {
	// a authenticated user is logged in
	if($con) {
		$graph_url = "https://graph.facebook.com/me?access_token="
		. $token;
		$user = json_decode(file_get_contents($graph_url));
		//echo '<h2>Welcome ' . $user->name . '!</h2>';
		try{
			$var = array();
			$api_call = $graph_url . '&fields=home.limit(10)';
			// get the home timeline
			$var = json_decode(file_get_contents($api_call));
					
			// parse the timeline
					
			foreach($var->home->data as $v){
				// attribute
				$fid = $v->from->id;
				// attribute
				$id_full = $v->id;
				// goes into DB
				$username = $v->from->name;
				// goes into DB
				$text = $v->message;
				if(!is_null($text)) { 
					// goes into DB
					$timestamp = $v->created_time;
					// attribute / goes into DB
					//$media = $v->type;
					/*
					if (($media == "status") || ($media == "video")){
						$url = "https://www.facebook.com/" . $fid . "/posts/";
					}
					elseif (($media == "link") || ($media == "picture")){
						$url = $v->link;
					
					*/
					$url = $v->actions[0]->link;
					//$post = new fbInfo($username, $text, $timestamp, $url, $media);//single FB object TO STORE
					//$post->printInfo();
					$a=new StreamObject($id_full,$url,$timestamp,$text,"",$username,"facebook");
					/*
					$a->id=$fid;
					$a->url=$url;
					$a->time=$timestamp;
					$a->body=$text;
					$a->title="";
					$a->sender=$username;
					$a->type="facebook";
					*/
					if(!empty($a)) {
						prepareObject($con,$a,$id);
					}
				}
			}
		} catch (FacebookApiException $e){
			$loginUrl = $facebook->getLoginUrl($params);
			echo 'Error: Please <a href="' . $loginUrl . '">login.</a>';
			error_log($e->getType());
			error_log($e->getMessage());
		}
} else{
	// prompt for login
	$loginUrl = $facebook->getLoginUrl($params);
	echo 'Please <a href="' . $loginUrl . '">login.</a>';
}	
}
?>

<?php
/*
	Function that creates a Reddit stream object and stores it into the database.
	@Param $con - connection to the DB
	@Param $userID - User ID of the currently logged in user
	@Param $accessToken -	Access token initially created when the user authorizes the app
*/
    function runReddit($con, $userID, $accessToken){
	if ($con){
	    $count = 10;
	    $client_id = 'SfYJ4wd8Dm3n1A';
	    $client_secret = 'ej5pAjjrueHjZyxjnb7NW_j8WF4';
	    $client = new OAUTH2\Client($client_id, $client_secret, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
	    $client->setAccessToken($accessToken);
	    $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);

	    $response = $client->fetch("https://oauth.reddit.com/hot.json", array('limit' => $count));
	    for ($i=0; $i < $count; $i++){
		    $title = $response['result']['data']['children'][$i]['data']['title'];
		    $score = $response['result']['data']['children'][$i]['data']['score'];
		    $url =  $response['result']['data']['children'][$i]['data']['url'];
		    $subreddit = $response['result']['data']['children'][$i]['data']['subreddit'];
		    $id = $response['result']['data']['children'][$i]['data']['id'];
		    $comments = "http://www.reddit.com" . $response['result']['data']['children'][$i]['data']['permalink'];
		    $time = $response['result']['data']['children'][$i]['data']['created'];

		    $rInfo = new StreamObject($id, $comments, $time, $url, $title, $subreddit, "reddit");
		    if (!empty($rInfo)){
		        prepareObject($con, $rInfo, $userID);
		    }
	    }
	}
    }
?>
