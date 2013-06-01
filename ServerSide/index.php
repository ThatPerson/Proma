<?php
	include("secrets.php");

//connection to the database
	$mysqli = new mysqli($hostname, $username, $password, $database);
	
	//$userid = 1;

	
	$todo_list = array();
	class todo {
		public $complete = 0;
		public $message = "";
		public $id = 0;
		public function __construct($message, $complete, $id) {
			$this->complete = $complete;
			$this->id = $id;
			$this->message = $message;
		}
	}
	
	
	$res = $mysqli->query("select * from pr_todo limit 10");
	
	for ($i = 0; $i < $res->num_rows; $i++) {
		$res->data_seek($i);
		$row_no = $res->fetch_assoc();
		array_push($todo_list, new todo($row_no["content"], $row_no["complete"], $row_no["id"]));
	}
	
	$checked = array();
	
	$quier = $mysqli->query("select pr_posts.*, pr_users.username from pr_posts, pr_users where pr_posts.fromr = pr_users.id order by pr_posts.dater desc limit 20");
	
?>
<!doctype html>
<html>
	<head>
		<title>proma</title>
		<link href='style.css' rel='stylesheet' type='text/css'>
		<script src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
		<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script>
		function slider() {
// 		left: 50%;
// margin-left: -500px;
// top: 50px;

// left:0px;
// 	width:250px;
// 	top:0px;
			if (document.body.scrollTop > 250) {//Show the slider after scrolling down 100px
				document.getElementById("menu").style.position = "fixed";
				document.getElementById("menu").style.left = "50%";
				document.getElementById("menu").style.marginLeft = "-500px";
				document.getElementById("menu").style.top = "50px";
			//	document.getElementById("menu").style.backgroundColor = "#222222";
			//	document.getElementById("menu").style.color = "#ffffff";
			} else {
				document.getElementById("menu").style.left = "0px";
				document.getElementById("menu").style.marginLeft = "0px";
				document.getElementById("menu").style.top = "0px";
				document.getElementById("menu").style.position = "relative";
			}
		}
		$(window).scroll(function () {
			slider();
		});	
		</script>
	</head>
	<body>
		<?php echo file_get_contents("header.html"); ?>
		<div class="subheader">
			<div class="headermain">
				<h2>CappU</h2>
				<p>Hello world!</p>
			</div>
		</div>
		<div class="content">
			<div class="sidebar" id="menu">
				
				<div class="sidebar_item flashcard">
					<h1 class="flashcard main">Todo</h1>
					
					<?php for ($i = count($todo_list)-1; $i >= 0; $i--) { ?>
						<?php if ($todo_list[$i]->complete == 1) { ?>
							<?php array_push($checked, $todo_list[$i]); ?>
						<?php } else { ?>
							<p class="flashcard main"><?php echo strip_tags($todo_list[$i]->message); ?></p>
						<?php } ?>
					<?php } ?>
					<?php for ($i = 0; $i < count($checked); $i++) { ?>
						<p class="flashcard main checked"><?php echo strip_tags($checked[$i]->message); ?></p>
					<?php } ?>
				</div>
				
			</div>
			<div class="mainbit">
				<?php
					for ($i = 0; $i < $quier->num_rows; $i++) {
						$quier->data_seek($i);
						$row_no = $quier->fetch_assoc();
				?>
					<div class="flashcard main">
						<h1 class="flashcard main"><?php echo strip_tags($row_no["title"]); ?></h1>
						<p class="flashcard main_nolog"><b><?php echo strip_tags($row_no["username"]); ?>:<?php echo $row_no["dater"]; ?></b><br><?php echo strip_tags($row_no["content"]); ?></p>
					</div>
				<?php
					}
				?>
			</div>
		</div>
	</body>
</html>
