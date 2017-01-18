<?php
session_start();

include_once 'dbconnect.php'; //include php file
	
if (isset($_POST['login'])) {
    if (isset($_POST['uname']) && isset($_POST['upassword'])) {
    	$name = mysqli_real_escape_string($con, $_POST['uname']); //escape all the characters that could damange database
    	$password = mysqli_real_escape_string($con,  md5(md5($name).$_POST['upassword']));
    	$result = mysqli_query($con, "SELECT * FROM User WHERE uname = '" . $name. "' and upassword = '" . $password . "'");
		$row = mysqli_fetch_array($result);
    	if ($row) {
    		$_SESSION['uname']=$row['uname'];
   			$_SESSION["upassword"]=$_POST["upassword"];	
        	if (isset($_POST['rememberme'])) {
           	 	/* Set cookie to last 1 year */
            	setcookie('uname', $_SESSION["uname"], time()+60*60*24*365);
            	setcookie('upassword',$_SESSION["upassword"], time()+60*60*24*365);
        	}
  		  	header("Location: UserInfo.php");
    	} else {
        	$errormsg = "We could not find a user with that username and password!!! Please try again";
    	}
    } else {
    	echo 'You must supply a username and password.';
	}
} 

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
     <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

</head>
<body>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- add header -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Cookzila</a>
        </div>
        <!-- menu items -->
        <div class="pull-right" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">Login</a></li>
                <li class="active"><a href="register.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
                <fieldset>
                    <legend>Login</legend>
                    
                    <div class="form-group">
                        <label for="uname">Enter Your User Name</label>
                        <input type="text" name="uname" id ="name" placeholder="Enter Your User Name" value="<?php if (isset($_COOKIE["uname"])) echo $_COOKIE["uname"]; ?>"class="form-control" /> <!-- value="<?php if (isset($_COOKIE["uname"])) echo $_COOKIE["uname"]; ?>"  -->
                    </div>

                    <div class="form-group">
                        <label for="upassword">Enter Your Password</label>
                        <input type="password" name="upassword" id ="password" placeholder="Your Password" value="<?php if (isset($_COOKIE["upassword"])) echo $_COOKIE["upassword"]; ?>" class="form-control" />  <!-- value="<?php if (isset($_COOKIE["upassword"])) echo $_COOKIE["upassword"]; ?>" -->
                    </div>
                    
					<div class="form-group">
						 Remember Me: <input type="checkbox" name="rememberme" value="1" /><br> <!--  -->
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" name="login" value="Login" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">    
       		 New User? <a href="register.php">Sign Up Here</a>
        </div>
    </div>
</div>

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
</body>
</html>