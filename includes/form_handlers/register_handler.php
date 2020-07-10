<?php
$fname=""; //First name
$lname=""; //First name
$em=""; //First name
$em2=""; 
$password=""; 
$password2="";
$date="";
$error_array=array();//holds error messages
if(isset($_POST['register_button'])) {
    //registeration form values
    $fname=strip_tags($_POST['reg_fname']); //strip html tags
    $fname=str_replace(" ","",$fname); //strip white spaces
    $fname=ucfirst(strtolower($fname)); //upper first letter
    $_SESSION['reg_fname']=$fname; //stores first name into session variable
    $lname=strip_tags($_POST['reg_lname']); //strip html tags
    $lname=str_replace(" ","",$lname); //strip white spaces
    $lname=ucfirst(strtolower($lname)); //upper first letter
    $_SESSION['reg_lname']=$lname; 
    $em=strip_tags($_POST['reg_email']); //strip html tags
    $em=str_replace(" ","",$em); //strip white spaces
    $em=ucfirst(strtolower($em)); //upper first letter
    $_SESSION['reg_email']=$em; 
    $em2=strip_tags($_POST['reg_email2']); //strip html tags
    $em2=str_replace(" ","",$em2); //strip white spaces
    $em2=ucfirst(strtolower($em2)); //upper first letter
    $_SESSION['reg_email2']=$em2; 
    $password=strip_tags($_POST['reg_password']);
    $password2=strip_tags($_POST['reg_password2']);
    $date=date("Y-m-d");
    if($em==$em2) {
        //check if email is in valid format
        if(filter_var($em, FILTER_VALIDATE_EMAIL)){
            
            $em=filter_var($em,FILTER_VALIDATE_EMAIL);
            
            //check if email alraeady exists
            $e_check=mysqli_query($con, "SELECT email FROM users WHERE email='$em'");
            //count the number of rows returned
            $num_rows=mysqli_num_rows($e_check);
            if ($num_rows >0 ) {
                array_push($error_array,"<span class='errorMessage'>Email already exists<br></span>") ;
            }
        }
             else{
            array_push($error_array,"<span class='errorMessage'>Invalid format<br></span>");
        }
    } else{
        array_push($error_array,"<span class='errorMessage'>Emails don't match<br></span>");
    }
    if(strlen($fname)>25 ||strlen($fname)<2) {
        array_push($error_array,"<span class='errorMessage'>Your first name must be between 2 and 25 characters<br></span>");
    }
    if(strlen($lname)>25 ||strlen($lname)<2) {
        array_push($error_array, "<span class='errorMessage'>Your last name must be between 2 and 25 characters<br></span>");
    }
    if($password!=$password2) {
        array_push($error_array, "<span class='errorMessage'>Your passwords don't match<br></span>");
    }
    else{
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
          array_push($error_array, "<span class='errorMessage'>Your password can only contain English characters or numbers<br></span>");
        }}
    if(strlen($password)>30 ||strlen($password)<3) {
        array_push($error_array,"<span class='errorMessage'>Your password must be between 3 and 30 characters<br></span>");
    }
     if(empty($error_array))  {
         $password=md5($password);//encrypt password before sending to db
         //Generate usrename by concatenating first and last names
         $username=strtolower($fname."_".$lname);
         $check_username_query=mysqli_query($con,"SELECT username FROM users WHERE username='$username'");
        $i=0;
        //if username exists add number to username
        while(mysqli_num_rows($check_username_query)!=0) {
            $i++;
            $username=$username."_".$i;
            $check_username_query=mysqli_query($con,"SELECT username FROM users WHERE username='$username'");
        }
       
            $profile_pic="assets/images/profile_pics/defaults/default-profile11.png";
            $query=mysqli_query($con,"INSERT INTO users VALUES ('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',') ");
            array_push($error_array,"<span style='color:#14c800;'>You're registered</span><br>");
            //clear session variables
            $_SESSION['reg_fname']="";
            $_SESSION['reg_lname']="";
            $_SESSION['reg_email']="";
            $_SESSION['reg_email2']="";
        } 
    }
    ?>