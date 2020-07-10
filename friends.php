<?php
include("includes/header.php");
 
//Get username parameter from url
if(isset($_GET['username'])) {
    $username = $_GET['username'];
}
else {
    $username = $userLoggedIn; //If no username set in url, use user logged in instead
}
?>
 
<div class="main_column column" id="main_column">
 <?php
$user_obj = new User($con, $username);
 
foreach($user_obj->getFriendsList() as $friend) {
 
    $friend_obj = new User($con, $friend);
 
    echo "<a href='$friend'>
            <img class='profilePicSmall' src='" . $friend_obj->getProfilePic() ."'>"
            . $friend_obj->getFirstAndLastName() . 
        "</a>
        <br>";
}
?>
</div>