<!-- This php file contains the code for the user registration process. 
The access to this page will be restricted for signed in user. 
We do this by checking out the session variable. -->
<?php
session_start();

if(!isset($_SESSION['uname'])) {
    header("Location: login.php");
}

include_once 'dbconnect.php';


if (isset($_POST["submit"])){
	if(!$_POST['profile']){
		$error="Please edit your profile";
	}
	
	if(isset($error)){
		$result = '<div class="alert alert-danger"><strong> There were error(s) in your form:</strong>'.$error.'</div>';
	} else{
    	$name = mysqli_real_escape_string($con, $_SESSION['uname']); //escape all the characters that could damange database
    	$sresult = mysqli_query($con, "SELECT * FROM Uprofile WHERE uname = '" . $name. "'");
		$row = mysqli_fetch_array($sresult);
    	if ($row) {	
  		  	if(mysqli_query($con, "UPDATE `Uprofile` SET `profile`='" . $_POST['profile'] . "' WHERE `uname`='" . $name . "' LIMIT 1")) {
            	$success = 1;
       		 } 
    	} else {
        	if(mysqli_query($con, "INSERT INTO Uprofile(uname, profile) VALUES('" . $name . "', '" . $_POST['profile'] . "')")) {
            	$success = 1;
       		 }
        	
    	}
		if(isset($success)) {
			$result = '<div class="alert alert-success"><strong> Thank you!</strong></div>';
		}

	}
}	

if (isset($_POST["comeback"])){
	header("Location: UserInfo.php");
}	
?>
<!doctype html>
<html>
<head>
    <title>Edit profile</title>
	<?php if(isset($result))echo $result; ?>
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<style>
	.profileForm{
		border:1px solid grey;
		border-radius: 10px;
		margin-top:20px;
	}
	
	form{
		padding-bottom:20px;
	}
	/* 
textarea{
		height: 120px;
	}
 */
</style>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 profileForm">
			<h3>Edit Your profile </h3>
			<?if (isset($result)) php echo $result; ?>
		
			<form method = "post">
				<div class="form-group">
					<textarea class="form-control" name ="profile"></textarea>
				</div>
				<div class="form-group">
             	    <input type="submit" name="submit" class="btn btn-success btn-lg" />
          	    </div>	
          	    <div class="form-group">
             	    <input type="submit" name="comeback" value ="Come Back" class="btn btn-success btn-lg" />
          	    </div>		
			</form>
			</div>
	 	</div>
	</div>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>