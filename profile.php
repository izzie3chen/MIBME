<?php
include_once("includes/header.php");
// include_once('includes/classes/User.php');
include_once('includes/classes/Post.php');
// session_destroy();
$message_obj=new Message($con, $userLoggedIn);

if(isset($_GET['profile_username'])) {
    $username=$_GET['profile_username'];
    //insert friend request acceptance notification
    $opened_query = mysqli_query($con, "UPDATE notifications SET opened='yes' WHERE user_to='$userLoggedIn' AND link='$username'");
    // the code above turns notifications white when opened
    $user_details_query=mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
    $user_array=mysqli_fetch_array($user_details_query);
    $num_friends=(substr_count($user_array['friend_array'],",")) -1;
}
if(isset($_POST['remove_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->removeFriend($username);
}
if(isset($_POST['add_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->sendRequest($username);
}
if(isset($_POST['respond_request'])) {
   header("Location:request.php");
}
//cancel request
if(isset($_POST['cancel_request'])) {
    $user = new User($con, $userLoggedIn);
    $user->cancelRequest($username);
}
if(isset($_POST['post_message'])) {
  if(isset($_POST['message_body'])) {
    $body = mysqli_real_escape_string($con, $_POST['message_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($username, $body, $date);
  }
  
  $link = '#profileTabs a[href="#messages_div"]';
  echo "<script> 
          $(function() {
              $('" . $link ."').tab('show');
          });
        </script>";


}
?>
<style>
.wrapper{
    margin-left: 0px;
    padding-left: 0px;
}

</style>
<div class="profile_left">
    <img src="<?php echo $user_array['profile_pic']?>" alt="">
    <div class="profile_info">
        <p> <?php echo "Posts: ".$user_array['num_posts']; ?></p>
         <p> <?php echo "Likes: ".$user_array['num_likes']; ?></p>
          <p> <?php echo "Friends: ".$num_friends; ?></p>
          <p><a href="friends.php">View Friends</a></p> 
    </div>
    <form action="<?php echo $username; ?>" method="POST">
    <?php
        $profile_user_obj=new User($con,$username);
        if($profile_user_obj->isClosed()) {
            header("Location:user_closed.php");
        }
        $logged_in_user_obj=new User($con,$userLoggedIn);
        if($userLoggedIn!=$username) {
            if($logged_in_user_obj->isFriend($username)) {
                echo '<input type="submit" name="remove_friend" class="danger" value="Unfriend"><br>';
            }
            else if ($logged_in_user_obj->didReceiveRequest($username)) {
                echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
            }
            else if ($logged_in_user_obj->didSendRequest($username)) {
                echo '<input type="submit" name="cancel_request" class="default" value="Respond Sent"><br>';}
            else {echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';}
            }

    ?>
    </form>
     <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Write a Post">
            <?php 
              if($userLoggedIn!=$username) {
                echo '<div class="profile_info_bottom">';
                echo $logged_in_user_obj->getMutualFriends($username) . " Mutual Friends";
                echo '</div>';
              }
            ?>
    </div>

<div class="profile_main_column column">
    <ul class="nav nav-tabs" role="tablist" id="profileTabs">
  <li class="nav-item">
    <a class="nav-link active" href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Messages</a>
  </li>
</ul>
<div class='tab-content'>
              <div  class="tab-pane active" id="newsfeed_div">
                    <div class="posts_area"></div>
                    <img id="loading" src="assets/images/icons/loading2.gif" alt="">
              </div>

              
              <div  class="tab-pane fade" id="messages_div">
                   <?php  
		
			echo "<h4>You and <a href='" . $username . "'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

			echo "<div class='loaded_messages' id='scroll_messages'>";
				echo $message_obj->getMessages($username);
			echo "</div>";
		?>



		<div class="message_post">
			<form action="" method="POST">
					 <textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
					 <input type='submit' name='post_message' class='info' id='message_submit' value='Send'>

			</form>

		</div>

		<script>
			var div = document.getElementById("scroll_messages");
			div.scrollTop = div.scrollHeight;
		</script>
              </div>
</div>


</div>


<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" style="position:absolute">Write a Post</h4>
      </div>
      <div class="modal-body">
       <p>What have you been up to?</p>
      <form class="profile_post" method="POST" action="index.php" enctype="multipart/form-data">
     
            <div class="form-group">
  
                <textarea class="form-control" name="post_body"></textarea>
                <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                <input type="hidden" name="user_to" value="<?php echo $username; ?>">
            </div>
      </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
      </div>
    </div>
  </div>
</div>
<script>
    var userLoggedIn='<?php echo $userLoggedIn; ?>';
    var profileUsername='<?php echo $username;  ?>';
    $(document).ready(function(){
        $('#loading').show(),
        $.ajax({

            url:"includes/handlers/ajax_load_profile_posts.php",
            type:"POST",
            data:"page=1&userLoggedIn=" + userLoggedIn +"&profileUsername="+profileUsername,
            cashe:false,
            success: function(data) {
                $('#loading').hide();
                $('.posts_area').html(data);
            }
        });
        $(window).scroll(function(){
            var height=$('.posts_area').height();
            var scroll_top=$(this).scrollTop();
            var page=$('.posts_area').find('.nextPage').val();
            var noMorePosts=$('.posts_area').find('.noMorePosts').val();
            if((document.body.scrollHeight==document.body.scrollTop+window.innerHeight)&&noMorePosts=='false') {
                 $('#loading').show();
                var ajaxReq= $.ajax({
                    url:"includes/handlers/ajax_load_profile_posts.php",
                    type:"POST",
                    data:"page="+page+"&userLoggedIn=" + userLoggedIn+"&profileUsername="+profileUsername,
                    cashe:false,
                    success: function(response) {
                        $('.posts_area').find('.nextPage').remove();
                        $('.posts_area').find('.noMorePosts').remove();
                        $('#loading').hide();
                        $('.posts_area').append(response);
                    }
                 });
            } return false;
    });
    });
</script>

</div>

</body>
</html>