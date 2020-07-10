<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';

//Declaring variables to prevent errors

?>
 <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
    
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet"> -->
     <!-- <link rel="stylesheet" href="./assets/css/register_style.css"> -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MibMe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   
    <link rel="stylesheet" href="./assets/css/test.css">
    
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon.ico">
    <script src="https://kit.fontawesome.com/1ce978f9fd.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
            
</head>
<body>
   
 <?php
        if(isset($_POST['register_button'])){
           echo ' 
           <script>
            $(document).ready(function() {
                $("#second").hide();
                $("#first").show();
            });
            </script>';
        }
    ?>
            <div class="container register">
                <div class="row">
                    <div class="col-md-3 register-left">
                       <img src="./assets/images/icons/pirate.png" alt="">
                        <h3>Welcome</h3>
                        <p class="display-6">A social network site without ad or privacy concern.</p>
                        <div class="row align-baseline">
                        <div class="col-4 col-sm-12">
                        <i class="fa fa-ad"></i><span> NO</span>
                        </div>
                        <div class="col-4 col-sm-12">
                        <i class="fa fa-heart"></i><span> ON</span>
                        </div>
                        <div class="col-4 col-sm-12">
                        <i class="fa fa-user-secret"></i><span> ON</span>
                        </div></div>
                       
                        
                      
                        <!-- <a  class="btn btn-success" href="">Test</a> -->
                        <input type="submit" name="" value="Login"  id="signin" class="signin"/><br/>
                    </div>

                    <div class="col-md-9 register-right" id="first">
             
                            <h3 class="register-heading">Miib</h3>
                            
                            <div class="row register-form justify-content-center" >
                            <div class="col-md-6">
                            <form action="register.php" method="POST">
                            <div class="form-group">
                            <input type="email" class="form-control" name="log_email" placeholder="Email Address" value="<?php 
                                if(isset($_SESSION['log_email'])){
                                    echo $_SESSION['log_email'];
                                } ?>" required>
                            </div>
                            <div class="form-group">
                            <input type="password" class="form-control" name="log_password" placeholder="Password" >
                            </div>
                            <?php
                                if(in_array("<span class='errorMessage'>Email or password was incorrect<br></span>",$error_array)) echo "Email or password was incorrect<br>";
                            ?>
                            <div class="form-group text-center">
                            <input type="submit" id="login_button" class="form-control signin" name="login_button" value="Login">
                            <a href="#" id="signup" class="signup  text-muted text-decoration-none ">Register Here</a><br>
                            <a href="requestReset.php" class="text-muted text-decoration-none ">Forgot Password?</a>
                        </div>
                            </form>
                            
                            </div></div></div>

                        <div class="col-md-9 register-right" id="second">
             
                            <h3 class="register-heading">Miib</h3>
                                <div class="row register-form " >
                               
                                    <div class="col-md-6" >
                                    <form action="register.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="reg_fname" placeholder="First Name *" value="<?php 
                                                if(isset($_SESSION['reg_fname'])){
                                                    echo $_SESSION['reg_fname'];
                                                }
                                                ?>" required/> 
                                            <?php if(in_array("<span class='errorMessage'>Your first name must be between 2 and 25 characters<br></span>",$error_array))   echo "Your first name must be between 2 and 25 characters<br>";
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Last Name *" name="reg_lname" value="<?php 
                                                if(isset($_SESSION['reg_lname'])){
                                                    echo $_SESSION['reg_lname'];
                                                }
                                                ?>" required/> 
                                                <?php if(in_array("<span class='errorMessage'>Your last name must be between 2 and 25 characters<br></span>",$error_array))   echo "Your last name must be between 2 and 25 characters<br>";
                                                ?>                                               
                                            
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="reg_email" class="form-control" placeholder="Your Email *" value="<?php 
                                                    if(isset($_SESSION['reg_email'])){
                                                        echo $_SESSION['reg_email'];
                                                    }
                                                    ?>" required/>
                                                <?php if(in_array("<span class='errorMessage'>Email already exists<br></span>",$error_array))   echo "<span class='errorMessage'>Email already exists<br></span>";
                                                ?>
                                                <?php if(in_array("<span class='errorMessage'>Invalid format<br></span>",$error_array))   echo "Invalid format<br>";
                                                ?>
                                                <?php if(in_array("<span class='errorMessage'>Emails don't match<br></span>",$error_array))   echo "Emails don't match<br>";
                                                ?>
                                        </div>
                                       
                                    </div>

                                    <div class="col-md-6">
                                        
                                        <div class="form-group">
                                             <input type="email" name="reg_email2" class="form-control" placeholder="Confirm Your Email *" value="<?php 
                                                                if(isset($_SESSION['reg_email2'])){
                                                                    echo $_SESSION['reg_email2'];
                                                                }
                                                                ?>" required />
                                        </div>
                                         <div class="form-group">
                                            <input type="password" name="reg_password" class="form-control" placeholder="Password *" value="" required/>
                                                <?php if(in_array("<span class='errorMessage'>Your passwords don't match<br></span>",$error_array))   echo "Your passwords don't match<br>";
                                                ?>
                                                <?php if(in_array("<span class='errorMessage'>Your password can only contain English characters or numbers<br></span>",$error_array))   echo "Your password can only contain English characters or numbers<br>";
                                                ?>
                                                <?php if(in_array("<span class='errorMessage'>Your password must be between 3 and 30 characters<br></span>",$error_array))   echo "Your password must be between 3 and 30 characters<br>";
                                                ?>                                            
                                        </div>
                                         <div class="form-group">
                                            <input type="password" class="form-control"  placeholder="Confirm Password *" value="" name="reg_password2" required />
                                        </div>
                                      
                                        <input type="submit" name="register_button" class=" btnRegister"  value="Register"/><br>
                                        <?php if(in_array("<span style='color:#14c800;'>You're registered</span><br>",$error_array))   echo "<span style='color:#14c800;'>You're registered</span><br>";
        ?>
                                        <!-- <div class="form-group">
                                         <a href="#" id="signin" class="signin login text-muted text-decoration-none">Already have an account? Click here</a>
                                        </div> -->
                                        </form>
                                    </div>
                                </div>
                            </div></div>
                            
                        
                    
                </div>

            </div>
            
        </body>
</html>