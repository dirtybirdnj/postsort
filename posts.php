<?php
	
class postReader {
	
	public function readCSV(){
		
		$file = fopen("posts.csv","r");
		$values = array();
		
		$first_row = true;
		
		while(! feof($file)){ 
			
			$arr = fgetcsv($file); 
			$key_arr = array();
			
			$key_arr['id'] = intval($arr[0]);
			$key_arr['title'] = $arr[1];
			$key_arr['privacy'] = $arr[2];
			$key_arr['likes'] = intval($arr[3]);
			$key_arr['views'] = intval($arr[4]);
			$key_arr['comments'] = intval($arr[5]);
			$key_arr['timestamp'] = $arr[6];																			
			
			if(!$first_row){ $values[] = $key_arr; }
			else { $first_row = false; }
			
		}
		
		fclose($file);
		return $values;
		
	}
	
	//Allows for filtering by field string value, or integer threshold
	public function filterBy($set,$type,$value){
		
		foreach($set as $item){
			
			if($item[$type] == $value || $item[$type] > $value){ $return[] = $item; }

		}
		
		return $return;
		
	}
	
	public function filterByStringLength($set,$type,$count){
		
		foreach($set as $item){
			
			if(strlen($item[$type]) < 40) $return[] = $item;
			
		}
		
		return $return;
		
	}
	
	public function getTopPosts($posts){
		
		//Gets all posts marked public
		$public_posts = $this->filterBy($posts,'privacy','public');
		
		//Returns posts with at least 10 comments
		$comments_posts = $this->filterBy($public_posts,'comments',10);

		//Returns posts with at least 9000 views
		$views_posts = $this->filterBy($comments_posts,'views',9000);
		
		//Remove posts with more than 40 chars in the title
		$title_posts = $this->filterByStringLength($views_posts,'title',40);	
				
		return $title_posts;
		
	}
	
	public function getOtherPosts($top_ids,$all_posts){
		
		foreach($all_posts as $post){
			
			if(!in_array($post['id'],$top_ids)) $return[] = $post;
			
		}
		
		return $return;
	}
		
	public function getIds($array){
		
		foreach($array as $item){ $keys[] = $item['id']; }
		return $keys;
	
	}
	
	public function getMostLiked($posts){
		
		$top_post = array('likes' => 0);
		
		foreach($posts as $post){
			
			if($post['likes'] > $top_post['likes']) $top_post = $post;
			
		}
		
		return $top_post;
		
	}
	
	public function outputJSON($arr){
		
		header('Content-Type: application/json');
		echo json_encode($arr);
		
	}	
	
	public function outputCSV($arr,$filename){
		
		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv"); 
		header("Content-Disposition:attachment;filename=$filename.csv"); 

		//If the first element is NOT an array
		if(!is_array($arr[0])){ fputcsv($output, $arr); } 
		
		//Else if we're outputing full records	
		else { 
		
			fputcsv($output,array('id','title','privacy','likes','views','comments','timestamp'));
			foreach($arr as $post) { fputcsv($output, $post); } 
		}

		fclose($output) or die("Can't close php://output");		
		
		
	}
	
	
}
	
?>