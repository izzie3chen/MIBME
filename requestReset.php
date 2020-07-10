<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './config/config.php';


if(isset($_POST["email"])) {
    $emailTo=$_POST["email"];
    $code=uniqid(true);
    $query=mysqli_query($con, "INSERT INTO resetPasswords(code,email) VALUES ('$code','$emailTo')");
    if(!$query) {
        exit("Error");
    }
    
    // Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'izbucket7@gmail.com';                     // SMTP username
    $mail->Password   = 'No19871029@';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('izbucket7@gmail.com', 'Mailer');
    $mail->addAddress($emailTo);     // Add a recipient
                
    $mail->addReplyTo('no-reply@gmail.com', 'Information');
   

    // Content
    $url="http://" .$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/resetPassword.php?code=$code";
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Your password reset link';
    $mail->Body    = "<h1>You requested a password reset</h1>
                        Click <a href='$url'>this link</a> to do so";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Reset password link has been sent to your email';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
    exit();
}





?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Pasword</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
         <form method="POST">
  <div class="form-group" >
    <label for="exampleInputEmail1">Email address</label>
    <input name="email" type="text" autocomplete="off" class="form-control" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Please enter your email address.</small>
  </div>
  <input type="submit" name="submit" style="background:#4cbbb9;border:none;" class="btn btn-primary" value="Reset Password">
</form>
    </div>
   
</body>
</html>

<!-- <form method="POST">
    <input type="text" name="email" placeholder="Email" autocomplete="off"><br><br>
    <input type="submit" name="submit" value="Reset email">
</form> -->