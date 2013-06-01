<?php
	include("../secrets.php");
//connection to the database
	$mysqli = new mysqli($hostname, $username, $password, $database);
	if (isset($_REQUEST['u']) && isset($_REQUEST['p'])) {
		$l = ("select * from pr_users where md5(username) = '".$mysqli->real_escape_string($_REQUEST['u'])."' and password = '".$mysqli->real_escape_string($_REQUEST['p'])."'");
		$ro = $mysqli->query($l);
		if ($ro->num_rows != 1) {
			echo "User does not exist";
			exit(0);
		}
		$ro->data_seek(0);
		$row_no = $ro->fetch_assoc();
		$level = $row_no["level"];
		$userid = $row_no["id"];
// 		levels are as follows
// 		1 = nothing
// 		2 = complete todos
// 		3 = new todos
// 		4 = new blog posts
// 		5 = new users
		if (isset($_REQUEST['complete'])) {
			if ($level > 1) {
				$que = "update pr_todo set complete = 1 where id = '".$mysqli->real_escape_string($_REQUEST['complete'])."'";
				echo "Completing todo ".$_REQUEST['complete']."";
			} else {
				echo "Could not complete query - You do not have the required permissions.";
				exit(0);
			}
		}
		
		if (isset($_REQUEST['todo'])) {
			if ($level > 2) {
				$que = "insert into pr_todo (content) values ('".$mysqli->real_escape_string($_REQUEST['todo'])."')";
				echo "Inserted";
			} else {
				echo "Could not complete query - You do not have the required permissions.";
				exit(0);
			}
		}
		
		if (isset($_REQUEST['vito'])) {
			$quer = "select * from pr_todo where complete = 0";
// 			echo $quer;
			$rio = $mysqli->query($quer);
			for ($i = 0; $i < $rio->num_rows; $i++) {
				$rio->data_seek($i);
				$row_no = $rio->fetch_assoc();
				echo $row_no["id"]." - ".$row_no["content"]."<br>";
			}
		}
		
		if (isset($_REQUEST['vibl'])) {
			$quer = "select pr_posts.*, pr_users.username from pr_posts, pr_users where pr_posts.fromr = pr_users.id order by pr_posts.dater desc limit 20";
// 			echo $quer;
			$rio = $mysqli->query($quer);
			for ($i = 0; $i < $rio->num_rows; $i++) {
				$rio->data_seek($i);
				$row_no = $rio->fetch_assoc();
				echo $row_no["id"]."::".$row_no["username"]." - ".$row_no["title"]."<br>";
			}
		}
		
		if (isset($_REQUEST['blog']) && isset($_REQUEST['title'])) {
			if ($level > 3) {
				$que = "insert into pr_posts (content, title, dater, fromr) values (\"".$mysqli->real_escape_string($_REQUEST['blog'])."\", \"".$mysqli->real_escape_string($_REQUEST['title'])."\", now(), '".$userid."')";
				echo "Inserted";
			} else {
				echo "Could not complete query - You do not have the required permissions.";
				exit(0);
			}
		}
		
		if (isset($_REQUEST['blro'])) {
			if ($level > 4) {
				$que = "DELETE FROM pr_posts WHERE id = '".$mysqli->real_escape_string($_REQUEST['blro'])."'";
				//echo $que;
				echo "Deleted";
			} else {
				echo "Could not complete query - You do not have the required permissions.";
				exit(0);
			}
		}
		
		if (isset($que)) {
			$mysqli->query($que);
		}
	} else {
		echo "No authentication.";
	}
?>