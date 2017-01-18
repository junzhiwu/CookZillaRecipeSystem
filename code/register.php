<!-- This php file contains the code for the user registration process. 
The access to this page will be restricted for signed in user. 
We do this by checking out the session variable. -->
<?php
session_start();

if(isset($_SESSION['uname'])) {
    header("Location: index.php");
}

include_once 'dbconnect.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['signup'])) {
    $uname = mysqli_real_escape_string($con, $_POST['uname']); // user name
    $oname = mysqli_real_escape_string($con, $_POST['oname']); // true name
    $upassword = mysqli_real_escape_string($con, $_POST['upassword']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);//confirm password
    $uemail = mysqli_real_escape_string($con, $_POST['uemail']);
	    
    if(!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/",$uname) || strlen($uname)<3 || strlen($uname)>15){
        $error = true;
        $uname_error = "User Name must start with alphabet character, and contain only 3 -15 characters (only alphanumeric or the underscore characters)";//how to check whether username already registered, case sensitive
    }
    if(!isset($uname_error)) { //valid uname, check repeation 
		$query = "SELECT * FROM User WHERE `uname` ="." '" . $uname . "'";
    	if($result = mysqli_query($con, $query)){
    		$row = mysqli_fetch_array($result);
   	 		if(isset($row)) {
    			$error = true;
        		$sameUname = "That username is already regisered. Do you want to log in?";
    		} 
    	} 
    }
    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z ]+$/",$oname)) {
        $error = true;
        $oname_error = "True Name must contain only alphabets and space";
    }
    if(strlen($upassword) < 6) {
        $error = true;
        $upassword_error = "Password must be at least of 6 characters";
    }
    if($upassword != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }
    if(!filter_var($uemail,FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $uemail_error = "Please enter a valid email address.";//how to check whether email already registered
    }
    if(!isset($uemail_error)) { //valid uemail, check repeation 
    	$query = "SELECT * FROM User WHERE `uemail` ="." '" . $uemail . "'";
    	if($result = mysqli_query($con, $query)){
    		$rowNum = mysqli_num_rows($result);
   	 		if($rowNum) {
    			$error = true;
        		$sameEmail = "That email address is already regisered. Do you want to log in?";
    		} 
    	} 
    }
    if (!$error) {
//     	echo "INSERT INTO User(uname, oname, upassword, uemail) VALUES('" . $uname . "', '" . $oname . "', '" . md5(md5($uname).$upassword) . "', '" . $uemail . "')";
    	// $query = INSERT INTO `User`(`uname`, `oname`, `upassword`, `uemail`) VALUES('" . $uname . "', '" . $oname . "', '" . $upassword . "', '" . $uemail . "')";
        // $quert = "UPDATE `User` SET `uemail` = 'ian@hotmail.com' WHERE `uname` = 'Ian' LIMIT 1"; //set all the emails if no WHERE, limit one even more than one are set, if no where, at most lose one email
        // mysqli_query($link, $query);
        if(mysqli_query($con, "INSERT INTO User(uname, oname, upassword, uemail) VALUES('" . $uname . "', '" . $oname . "', '" . md5(md5($uname).$upassword) . "', '" . $uemail . "')")) {
            $successmsg = "Successfully Registered! <a href='login.php'>Click here to Login</a>";
        } else {
          	$errormsg = "Error in registering...Please try again later!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- 
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
 -->
     <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

</head>
<body>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- add header -->
        <div class="navbar-header pull-left">
            <a class="navbar-brand" href="index.php">Cookzila</a>
        </div>
        <!-- menu items -->
        <div class="pull-right" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="login.php">Login</a></li>
                <li class="active"><a href="register.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                    <legend>Sign Up</legend>

                    <div class="form-group">
                        <label for="uname">User Name</label>
                        <input type="text" name="uname" placeholder="Set your User Name" required value="<?php if($error) echo $uname; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($uname_error)) echo $uname_error."<br/>";
                        								if (isset($sameUname)) echo $sameUname;?></span>
                    </div>
                    <div class="form-group">
                        <label for="oname">True Name</label>
                        <input type="text" name="oname" placeholder="Enter your Full Name" required value="<?php if($error) echo $oname; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($oname_error)) echo $oname_error; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="uemail">Email</label>
                        <input type="email" name="uemail" placeholder="Enter Your Email" required value="<?php if($error) echo $uemail; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($uemail_error)) echo $uemail_error."<br/>"; 
                        								if (isset($sameEmail)) echo $sameEmail; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="upassword">Password</label>
                        <input type="password" name="upassword" placeholder="Set Your Password" required value="<?php if($error) echo $upassword; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($upassword_error)) echo $upassword_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="cpassword">Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="Confirm Your Password" required value="<?php if($error) echo addslashes($cpassword); ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">    
        Already Registered? <a href="login.php">Login Here</a>
        </div>
    </div>
</div>
<!-- 
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
 -->

</body>
</html>