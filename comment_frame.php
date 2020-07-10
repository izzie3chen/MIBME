  <?php
        require 'config/config.php';
        
        include('includes/classes/User.php');
        include('includes/classes/Post.php');
        include('includes/classes/Notification.php');

        if (isset($_SESSION['username'])) {
            $userLoggedIn=$_SESSION['username'];
            $user_details_query=mysqli_query($con,"SELECT * FROM users WHERE username='$userLoggedIn'");
            $user=mysqli_fetch_array($user_details_query);
        }else{
            header("Location:register.php");
        }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
    <style type="text/css">
    *{
        font-size: 12px;
        font-family: "Roboto",sans-serif;

    }
</style>
  
<script>
    function toggle() {
        var element =document.getElementById('comment_section');
        if (element.style.display=="block") {
            element.style.display="none";
        }
        else{
            element.style.display="block";
        }

    }
</script>
<?php
    if(isset($_GET['post_id'])){
        $post_id=$_GET['post_id'];
    }
    $user_query=mysqli_query($con,"SELECT added_by, user_to FROM posts WHERE id='$post_id'");
    $row=mysqli_fetch_array($user_query);
    $posted_to=$row['added_by'];
    $user_to=$row['user_to'];
    if(isset($_POST['postComment' . $post_id])){
        $post_body=$_POST['post_body'];
        $post_body=mysqli_escape_string($con,$post_body);
        $date_time_now=date("Y-m-d H:i:s");
        $insert_post=mysqli_query($con,"INSERT INTO comments VALUES('','$post_body','$userLoggedIn','$posted_to','$date_time_now','no','$post_id')");
        if($posted_to != $userLoggedIn) {
             $notification = new Notification($con, $userLoggedIn);
             $notification->insertNotification($post_id, $posted_to, "comment");
        } 
        if ($user_to != 'none' && $user_to != $userLoggedIn) {
             $notification = new Notification($con, $userLoggedIn);
            $notification->insertNotification($post_id, $user_to, "profile_comment");
        }
        $get_commenters=mysqli_query($con, "SELECT * FROM comments WHERE POST_ID='$post_id'");
        $notified_users=array();
        while ($row=mysqli_fetch_array($get_commenters)) {
            if($row['posted_by'] != $posted_to && $row['posted_by'] != $user_to 
            && $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'],$notified_users)) {
                 $notification = new Notification($con, $userLoggedIn);
             $notification->insertNotification($post_id, $row['posted_by'], "comment_non_owner");
             array_push($notified_users, $row['posted_by']);
            }
        }

        echo "<p>Comment Posted</p>";
    }
?>

<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id;?> " method="POST">
<textarea name="post_body" id="" ></textarea>
<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post">
</form>
<?php
$get_comments=mysqli_query($con,"SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id DESC");
$count=mysqli_num_rows($get_comments);
if($count!=0) {
    // while ($comment =mysqli_fetch_array($get_comments)) {
    //     $comment_body=$comment['post_body'];
    //     $posted_to=$comment['posted_to'];
    //     $posted_by=$comment['posted_by'];
    //     $date_added=$comment['date_added'];
    //     $removed=$comment['removed'];
    //added the code below to delete post
    while($comment = mysqli_fetch_array($get_comments)) {
 
	$table_id = $comment['id'];
	$comment_body = $comment['post_body'];
	$posted_to = $comment['posted_to'];
	$posted_by = $comment['posted_by'];
	$date_added = $comment['date_added'];
	$removed = $comment['removed'];
 
	if($posted_by === $userLoggedIn) {
 
		$delete_button = "<button class='delete_comment btn-danger' id='$table_id'>X</button>";
	}
 
	else {
 
		$delete_button = "";
    } 
    
    //added the code below to make links clickable
    $body_array = preg_split("/[ ]+|\n/", $comment_body);
 
foreach($body_array as $key => $value) {
 
	if(stripos($value, "www") !== false || stripos($value, "http") !== false) {
 
		if(stripos($value, "http://") === false && stripos($value, "https://") === false) {
 
		 	$value = "<p><a target='_blank' href='https://" . strip_tags($body_array[$key]) . "' title='Click to go to link'>" . strip_tags($body_array[$key]) . "</a></p>";
		 	$body_array[$key] = $value;
		 }
 
		 else if(stripos($value, "http://") !== false) {
 
		 	$value = "<p><a target='_blank' href='" . strip_tags($body_array[$key]) . "' title='Click to go to link'>" . strip_tags($body_array[$key]) . "</a></p>";
			$body_array[$key] = $value;
		 }
 
		 else if(stripos($value, "https://") !== false) {
 
			$value = "<p><a target='_blank' href='" . strip_tags($body_array[$key]) . "' title='Click to go to link'>" . strip_tags($body_array[$key]) . "</a></p>";
			$body_array[$key] = $value;
		}
 
		$comment_body = implode(" ", $body_array);
	}
 
}

           $date_time_now=date("Y-m-d H:i:s");
            $start_date=new DateTime($date_added);
            $end_date=new DateTime($date_time_now);
            $interval=$start_date->diff($end_date);
            if($interval->y>=1) {
                if($interval==1) {
                    $time_message=$interval->y."year ago";
                }else{
                    $time_message=$interval->y."years ago";
                }
            }else if ($interval->m>=1) {
                if($interval->d==0) {
                    $days= " ago";
                }else if ($interval->d ==1) {
                    $days=$interval->d." day ago";
                }else{
                    $days=$interval->d." days ago";
                }
                if($interval->m==1) {
                    $time_message=$interval->m ." month".$days;
                } else{
                    $time_message=$interval->m ." months".$days;
                }
            } else if ($interval->d>=1) {
                if ($interval->d ==1) {
                    $time_message="Yesterday";
                }else{
                    $time_message=$interval->d." days ago";
                }
            } else if($interval->h>=1) {
                if ($interval->h ==1) {
                    $time_message=$interval->h." hour ago";
                }else{
                    $time_message=$interval->h." hours ago";
                }
            }
            else if($interval->i>=1) {
                if ($interval->i ==1) {
                    $time_message=$interval->i." minute ago";
                }else{
                    $time_message=$interval->i." minutes ago";
                }
        }
        else  {
                if ($interval->s<30) {
                    $time_message="just now";
                }else{
                    $time_message=$interval->s." seconds ago";
                }
    }
    $user_obj=new User($con,$posted_by); ?>
    <div class="comment_section">
    <a href="<?php echo $posted_by ?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic(); ?>" alt="" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
    <a href="<?php echo $posted_by ?>" target="_parent"><b><?php echo $user_obj->getFirstAndLastName(); ?></b> </a>
    &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . $delete_button . "<br>" . $comment_body;?>
    <hr>
    </div>
    <?php 

    }
}
else{
    echo "<center><br><br>No comments to show. </center>";
}
?>
   <!-- added the code below to delete post -->
       <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
             
  
	<script>
		
		$(function(){
 
			$(".delete_comment").on("click", function(e){
 
				let tableId = this.id;
				let postId = '<?php echo $post_id; ?>';
 
				$.post("includes/handlers/delete_comment.php", {id:tableId}, function(){
 
					    window.location.href = "comment_frame.php?post_id=" + postId;
 
						
				});
			});
 
		});
 
	</script>
 
</body>
</html>