<?php
require '../../config/config.php';
if(isset($_GET['post_id'])) {
    $post_id=$_GET['post_id'];
}
if(isset($_POST['result'])) {
    if($_POST['result']=='true'){
        $username=$_SESSION['username'];
         $query=mysqli_query($con, "UPDATE posts SET deleted='yes' WHERE id='$post_id'"); 
         $update_num_posts=mysqli_query($con,"UPDATE users SET num_posts=num_posts-1 WHERE username='$username'");
       
        // $query=mysqli_query($con, "SELECT COUNT(deleted) FROM posts WHERE deleted='yes' AND id='$post_id'");
                         
    }
}
?>
<!-- UPDATE posts SET deleted='yes' WHERE id='$post_id' -->
<!-- "DELETE FROM posts WHERE id='$post_id'" -->
