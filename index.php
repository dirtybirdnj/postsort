<?php
		
include_once('posts.php');

if($_GET){
	
	
	$reader = new postReader();
	$posts = $reader->readCSV();
	
	$top_posts = $reader->getTopPosts($posts);
	$top_ids = $reader->getIds($top_posts);
	
	$other_posts = $reader->getOtherPosts($top_ids,$posts);
	$other_ids = $reader->getIds($other_posts);
	
	$full_records = false;
	if(isset($_GET['fullrec'])) $full_records = true;
	
	if($_GET['dataset'] == 'top'){ 

		if($full_records){ $ids = $top_posts; }
		else { $ids = $top_ids; }
	}
	
	if($_GET['dataset'] == 'other'){ 
		
		if($full_records){ $ids = $other_posts; }
		else { $ids = $other_ids; }
		
	}
	if($_GET['dataset'] == 'daily'){ 
	
		//Ids is returned as an array so that it works properly with the outputCSV function
		$ids = array(0 => $reader->getMostLiked($top_posts)); 
	}
	
	if($_GET['format'] == 'json'){ $reader->outputJSON($ids); } 
	else { $reader->outputCSV($ids,$_GET['dataset']); }
	
	//no output after the $_GET block, since we are passing CSV/JSON headers
	die();	
	
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Post Sorting Code Test</title>
</head>
<body>
	<h2>Post Sorting Code Test</h2>
	<p>Mat Gilbert, 2015</p>
	
	<form action="index.php" method="get">
		<p>Format:
		<select name="format">
			<option value="json">JSON</option>	
			<option value="csv">CSV</option>
		</select>
		</p>
		
		<p>Data:
		<select name="dataset">
			<option value="top">Top Posts</option>
			<option value="other">Other Posts</option>
			<option value="daily">Top Daily Post</option>			
		</select>	
		</p>
		
		<p>Full Records: <input type="checkbox" name="fullrec" value="true"/></p>
		
		<p><input type="submit"/></p>
	</form>
</body>
</html>