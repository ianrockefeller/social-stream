<?php
require_once 'includes/functions.php';

$con=dbCon();
if($con) {
	$sql="select user_id from user";
	$result=$con->query($sql);
	$uid=array();
	if($result->num_rows>0) {//for each user
		while($r=$result->fetch_assoc()) {
			$uid[]=$r['user_id'];
		}
		mysqli_free_result($result);
		foreach($uid as $id) {//for each user
			//getRSS($con,$id);
			$result=$con->query("select url from rss where user_id=".$id);
			$urla=array();
			while($url=$result->fetch_assoc()) {//get all urls for user
				$urla[]=$url['url'];
			}
			mysqli_free_result($result);
			
			$add=array();//new streamobjects to add to DB
			
			foreach($urla as $url) {//for each url for each user
				$x=new SimpleXmlElement(file_get_contents($url));//get new rss
				//get the title to compare
				$result=$con->query("select title from object where user_id=".$id." and type='rss' and sender='".$url."'");
				$isNew=0;//dup counter
				
				foreach($x->channel->item as $entry) {
					while($title=$result->fetch_assoc()) {
						if(strcmp($entry->title,$title['title'])==0) {
							$isNew++;
							//echo 'equal'.PHP_EOL;
							//echo 'comparing '.$entry->title.' to '.$title['title'].PHP_EOL;
						}
					}
					if($isNew==0) {
						//id,url,time,body,title,sender,type
						$add[]=new StreamObject(null,$entry->link,$entry->pubdate,$entry->description,$entry->title,$url,"rss");
					}
				}
				mysqli_free_result($result);
			}
			if(count($add)!=0) {
				foreach($add as $a) {//for each StremObject
					insert_object($con,$a,$id);
				}
			} 
		}
	}
}
?>