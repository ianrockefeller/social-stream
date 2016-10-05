<?php
/*
Stream Object:
contains all fields in the object database. All elements
to be stored in the database must fit into this class
*/
class StreamObject {

    public $id, $url, $time, $body, $title, $sender, $type;

    public function __construct($id, $url, $time, $body, $title, $sender, $type) {
        $this->id = $id;
		$this->url = $url;
        $this->time = $time;
        $this->body = $body;
        $this->title = $title;
        $this->sender = $sender;
        $this->type = $type;
        
    }
}

/*
login(db connection,user's email, password):
takes a connection to the db and looks for a 
user in the database. If valid credentials they 
are logged in, else redirected to the register page
*/
function login($con,$email,$pw) {
    $success=false;
    $stmt=$con->prepare("SELECT user_id FROM user where email=? and pw=?");
    $stmt->bind_param('ss',$email,$pw);
    $stmt->execute();
    $stmt->bind_result($id);
         
    if($stmt->fetch()) {
      session_start();    
      $_SESSION['id']=$id;
      $_SESSION['email']=$email;
      $success=true;
    }
    return $success;
}

/*
getFeed(db connection, user id):
looks up all stream objects that belong to said
user and displays them in html
*/
function getFeed($con, $user) {
    if(!is_int($user)) {
        echo 'Error, not a user id';
        return false;
    }
    $result=$con->query("select * from object where user_id=".$user." order by time desc");
    
	echo "<div id='streamContainer'>".PHP_EOL; 
	
    while($row=$result->fetch_assoc()) {
		echo "<ul style='list-style-type: none'>";
		echo "<li style='border-style:solid;padding:5px;'>";
		echo '<a href="'.$row['url'].'" target="_blank">';
        echo "<div id='streamObject'>";


	if ($row['type'] == 'facebook'){
		echo '<img src="images/fb_icon.png" />';
	}
	elseif ($row['type'] == 'twitter'){
		echo '<img src="images/twitter_icon.png" />';
	}
	elseif ($row['type'] == 'reddit'){
		echo '<img src="images/reddit_icon.png" />';
	}
	elseif ($row['type'] == 'rss'){
		echo '<img src="images/rss_icon.png" />';
	}

	echo "<h3>".$row['title']."</h3>";
	if ($row['type'] == 'reddit'){
	    echo "<div><h3>/r/" . $row['sender'] . "</h3></div>";
	}
	elseif ($row['type'] != 'rss'){
            echo "<div><h3>". $row['sender'] ."</h3></div>";
	}
		echo "<div><p>" . $row['body'] . "</p></div><div>". $row['time'] ."</div></div>";
		echo "</a></li></ul>".PHP_EOL;
	}
	
    echo "</div>";
}

/* gets an RSS feed 
HERE FOR LEARNING PURPOSES
*/
function getRSSFeed($feed_url) {
    //call for feed
    $content = file_get_contents($feed_url);
    $x = new SimpleXmlElement($content); //put returned data into object 
    return $x;

    /*
      echo '<div id="streamContainer">';

      foreach($x->channel->item as $entry) {
      echo "<div id='streamObject'><h3><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></h3>";
      echo "<div><p>" . $entry->description . "</p></div></div>";
      }
      echo "</div>"; */
}

/* 
dbCon()
sets up db connection. Here you can find our
sensetive information. 
*/
function dbCon() {
    $host = 'localhost';
    $username = 'root';
    $password = 'dandymonkey';
    $dbname = 'StreamDB';

    $con = new mysqli($host, $username, $password, $dbname);

    if ($con->connect_errno) {
        //echo 'Failed to connect to MySQL: ';
        mysqli_close($con);
        return 0;
    } else {
        return $con;
    }
}

/*
insert_rss(db connection, user id, url to insert):
inserts a given url into the rss table to be 
looked up during the cronjob
*/
function insert_rss($con,$id,$url) {
	$isDup=false;
	
	$result=$con->query("select url from rss where user_id=".$id);
	while($r=$result->fetch_assoc()) {
		if(!strcmp($r['url'],$url)) {
			$isDup=true;
		}
	}
	mysqli_free_result($result);
	
	if(!$isDup) {
		$stmt = $con->prepare("insert into rss (user_id,url) values (?,?)");
		$stmt->bind_param('is', $id,$url);
		$stmt->execute();
		$stmt->close();
		return true;
	}
	return false;	
}

//this is for inserting an array of stream objects that were rss into
//the object table
//not used, here for reference
function a_insert_rss($con, $arr,$id) {
    foreach ($arr as $n) {
        insert_object($con, $n, $id);
        //add conditional for safety
    }
}

//registers a user
function insert_user($con, $user, $pw) {
	if(test_user($con,$user)) {
		$stmt = $con->prepare("insert into user (email,pw) values (?,?)");
		$stmt->bind_param('ss', $user, $pw);
		$stmt->execute();
	  
		return true;
	}
	return false;
}

/*
test_user(dbcon, user id):
looks up in the database if a user exists or not
returns true if the person is not found
*/
function test_user($con,$user) {
	$sql="select user_id from user where email='".$user."'";
	$result=$con->query($sql);
	$test=0;
	if($result) {
		while($r=$result->fetch_object()) {
			$test++;
		}
	}
	
	if($test!=0) return false;
	else return true;
}

//insert_object(database connection, stream object)
//takes the finalized streamobject and inserts it into the database
function insert_object($con,$so,$id) {
    $stmt = $con->prepare("insert into object (user_id,url,title,body,sender,type,pid) values (?,?,?,?,?,?,?)");
    //print_r($so->url. '   '.$id);
	//print_r($so->body);
	//print_r($so->title);
	//print_r($so->time);
	//print_r($so->type);
	if(!empty($so) && $id>0) {
		$stmt->bind_param('issssss',$id, $so->url, $so->title, $so->body, $so->sender, $so->type,$so->id);
		$stmt->execute();
	}
}

/*
prepareObject():
@params:db connection, stream object, user id
does some before entering into the database to make sure there 
isn't copies
*/
function prepareObject($con,$so,$id) {
	//get last 10 types of object from database from id
	$sql="select pid from object where type='".$so->type."' and user_id=".$id." order by time desc";	
	$result=$con->query($sql);
	$ra=array();
	$a=array();
	$dup=false;
	
	while($r=$result->fetch_assoc()) {
		$ra[]=$r['pid'];
	}
	
	foreach($ra as $r) {
		if(strcmp($so->id,$r)==0) {//equal
			$dup=true;
		}
	}

	if(!$dup){
		insert_object($con,$so,$id);
	}
}

/* function for refreshing RSS in database
deprecated and here for reference */
function getRSS($con, $user) {
    $stmt = $con->prepare("select url from rss where user_id=?");
    $stmt->bind_param('i', $user);
    $stmt->execute();
	$stmt->bind_result($url);
	$urlArray=array();
    $add = array(); //new streamobjects
    while($stmt->fetch()) {
		$urlArray[]=$url;
	}

	if(count($urlArray)>0 && $user>0) {
		foreach($urlArray as $n) {
			$st = $con->prepare("select title from object where user_id=? and type='rss' and sender=?");
			$st->bind_param('is', $user, $n);
			$st->execute();
			$st->bind_result($r);
			$x = getRSSFeed($n);
			//test
			$xml = $x->channel->item;
			$xTitle = $xml->title;
			$isNew = 0;
			$xmlArray=array();
			while($stmt->fetch()) {
				$xmlArray[]=$r;
			}	
			foreach($xmlArray as $x) {
				if (strcmp($x->title, $xTit-le) == 0) {
					$isNew++;
				}
			}
			if ($isNew == 0) {
				$add[] = new StreamObject(null, $xml->link, $xml->pubdate, $xml->description, $xml->title, $n, 'rss');
			}
		}
		//FINISH 
		//print_r($add);
		if (count($add) != 0) {
			a_insert_rss($con, $add, $user);
		}
	}
}

/*
set*Info():
This next set of functions are for entering access tokens and secrets, 
given a specific user, into the database to be used by the background 
process to call the APIs
*/
function setTwitterInfo($con,$id,$token,$secret) {
	$stmt = $con->prepare("update user set twitter=?, tpw=? where user_id=?");
    $stmt->bind_param('ssi',$token,$secret,$id);
    $stmt->execute();
}
function setFacebookInfo($con,$id,$token,$secret) {
	$stmt = $con->prepare("update user set facebook=?, fbpw=? where user_id=?");
    $stmt->bind_param('ssi',$token,$secret,$id);
    $stmt->execute();	
}

function setRedditInfo($con, $id, $token, $refresh = null){
    $stmt = $con->prepare("update user set reddit=?, rpw=? where user_id=?");
    $stmt->bind_param('ssi',$token,$refresh,$id);
    $stmt->execute();
}
?>  
