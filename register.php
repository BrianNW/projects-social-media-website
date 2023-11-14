<?php

//starts a session to store the variable values into session variable
session_start();

//create new sql connection
$conn = mysqli_connect("localhost", "root", "", "socialmediadb");

//error check
if(mysqli_connect_errno()){
    echo "Failed to connect: " . mysqli_connect_errno();
};

// delcaring variables
$fname = ""; //First name
$lname = ""; //Last name
$em = ""; //Email
$em2 = ""; //Email 2
$password = ""; //Password
$password2 = ""; //Password2
$date = ""; //Sign up date
$error_array = array(); //Holds error message

if(isset($_POST['register_button'])){

    //FIRST NAME
    //Register form values (strip_tags) takes away html tags
    $fname = strip_tags($_POST['reg_fname']);
    //Replaces space with no spaces
    $fname = str_replace(' ', '', $fname);
    //Take the variable, lowercase then capitalize first letter
    $fname = ucfirst(strtolower($fname));
    //Store first name into session variable
    $_SESSION['reg_fname'] = $fname;

    //LAST NAME
    //Register form values (strip_tags) takes away html tags
    $lname = strip_tags($_POST['reg_lname']);
    //Replaces space with no spaces
    $lname = str_replace(' ', '', $lname);
    //Take the variable, lowercase then capitalize first letter
    $lname = ucfirst(strtolower($lname));
    //Store last name into session variable
    $_SESSION['reg_lname'] = $lname;

    //EMAIL
    //Register form values (strip_tags) takes away html tags
    $em = strip_tags($_POST['reg_email']);
    //Replaces space with no spaces
    $em = str_replace(' ', '', $em);
    //Take the variable, lowercase then capitalize first letter
    $em = ucfirst(strtolower($em));
    //Store Email into session variable
    $_SESSION['reg_email'] = $em;

    //EMAIL2
    //Register form values (strip_tags) takes away html tags
    $em2 = strip_tags($_POST['reg_email2']);
    //Replaces space with no spaces
    $em2 = str_replace(' ', '', $em2);
    //Take the variable, lowercase then capitalize first letter
    $em2 = ucfirst(strtolower($em2));
    //Store Email2 into session variable
    $_SESSION['reg_email2'] = $em2;

    //PASSWORD
    //Register form values (strip_tags) takes away html tags
    $password = strip_tags($_POST['reg_password']);
    $password2 = strip_tags($_POST['reg_password2']);

    $date = date("Y-m-d"); //get current date

    if($em == $em2) {   

        if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

            $em = filter_var($em, FILTER_VALIDATE_EMAIL);  

            //check if email exists
            $e_check = mysqli_query($conn, "SELECT email FROM users WHERE email = '$em'");

            //Count number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0) {
                array_push($error_array, "Email already in use. <br>");
            }
            
        }
        else {
            array_push($error_array, "Invalid email format. <br>");
        }

    } else {
        array_push($error_array, "Emails don't match. <br>");
        }
 
    if(strlen($fname) > 25 || strlen($fname) < 2 ) {
        array_push($error_array, "Your first name must be between 2 to 25 characters. <br>");
    }

    if(strlen($lname) > 25 || strlen($lname) < 2 ) {
        array_push($error_array, "Your last name must be between 2 to 25 characters. <br>");
    }

    if($password != $password2) {
        array_push($error_array, "Your password must match. <br>");
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)){
            array_push($error_array, "Your password can only contain English characters or numbers. <br>");
        }
    }

    if(strlen($password) > 30 || strlen($password < 5)) {
        array_push($error_array, "Your password must be between 5 and 30 characters. <br>");
    }

    //if there are no errors during registration, encrypt the password
    if(empty($error_array)) {
        $password = md5($password);

        //Generate a username by concatenating first & last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        $i = 0;
        //if username exists add number to username
        // Future update - allow user to create custom username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; //Add 1 to i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
        }

        //profile pic assignment.
        $rand = rand(1,2); //pick a random number between 1 and 2

        if($rand == 1)
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png"; 
        else if($rand == 2)
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_emerald.png"; 

    }

 


}

?>


<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>

        <form action="register.php" method="POST">
            <input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])) { echo $_SESSION['reg_fname'];} ?>"> 
            <br>
            <?php if(in_array("Your first name must be between 2 to 25 characters. <br>", $error_array)) echo "Your first name must be between 2 to 25 characters. <br>"; ?>
            
            <input type="text" name="reg_lname" placeholder="Last Name" value="<?php if(isset($_SESSION['reg_lname'])) { echo $_SESSION['reg_lname'];} ?>">
            <br>
            <?php if(in_array("Your last name must be between 2 to 25 characters. <br>", $error_array)) echo "Your last name must be between 2 to 25 characters. <br>"; ?>

            <input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION['reg_email'])) { echo $_SESSION['reg_email'];} ?>"> 
            <br>  

            <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php if(isset($_SESSION['reg_email2'])) { echo $_SESSION['reg_email2'];} ?>">
            <br>  
            <?php if(in_array("Email already in use. <br>", $error_array)) echo "Email already in use. <br>";  
            else if(in_array("Invalid email format. <br>", $error_array)) echo "Invalid email format. <br>";
            else if(in_array("Emails don't match. <br>", $error_array)) echo "Emails don't match. <br>"; ?>        

            <input type="password" name="reg_password" placeholder="Password" >
            <br>

            <input type="password" name="reg_password2" placeholder="Confirm Password" >
            <br>
            <?php if(in_array("Your password must match. <br>", $error_array)) echo "Your password must match. <br>";  
            else if(in_array("Your password can only contain English characters or numbers. <br>", $error_array)) echo "Your password can only contain English characters or numbers. <br>";
            else if(in_array("Your password must be between 5 and 30 characters. <br>", $error_array)) echo "Your password must be between 5 and 30 characters. <br>"; ?>   

            <input type="submit" name="register_button" value="register">


        </form>
        
    </body>
</html>