<?php
	session_start();
	
	if(!isset($_SESSION['uname'])) {
   	 header("Location: login.php");
	}
	include_once 'dbconnect.php';
	
	// if(!isset($_SESSION['rid'])){
//     header("Location: index.php");
// 	}
	
	$uname = $_SESSION['uname'];
	if(isset($_GET['rid'])) $_SESSION['rid'] = $_GET['rid'];
	$rid = $_SESSION['rid'];

	
	if(isset($_POST['submit'])){// 
		if (isset($_FILES["file"]["name"])) {

    	$name = $_FILES["file"]["name"];
    	$tmp_name = $_FILES['file']['tmp_name'];
    	$error = $_FILES['file']['error'];

    		if (!empty($name)) {
        		$location = 'Images/';
				$TargetPath=time().$name;
        		if(move_uploaded_file($tmp_name, $location.$TargetPath)){
//             		echo 'Uploaded';
            		$uname ="Yufei";
//             		$rid = 1;
    				if(mysqli_query($con, "INSERT INTO ReviewImg (rid,uname,ReviewImgDir) VALUES ('".$rid."', '".$uname."', '".$TargetPath."')")){
//     		    		echo "Successful";
						 $successmsg = "Successful";

        			} else {
          				$errormsg = "Error query!";
        			}
        		}
    		} else {
        		$fileError = 'please choose a file';
    		}
		}else {
			$fileError = 'please choose a file';
		}
	}
   
    	
	$error1 = false;
	if (isset($_POST['submit2'])) {
		if(!$_POST['reviewtitle']){
			$error1 = true;
			$titleError="Please enter your reviewtitle";
		}
		if(!$_POST['rate']){
			$error1 = true;
			$rateError="Please enter your rate";
		} else {
			if($_POST['rate'] >5 or $_POST['rate'] <0) {
        		$error1 = true;
        		$rateError = "rate should between 0 and 5";
   		 	}
   		 }

    	 if (!$error1){
    	 	$reviewtitle = mysqli_real_escape_string($con, $_POST['reviewtitle']); // user name
    		$comment = mysqli_real_escape_string($con, $_POST['comment']); // true name
    		$rate = mysqli_real_escape_string($con, $_POST['rate']);
    		if(mysqli_query($con, "INSERT INTO Review(rid, rrname,reviewTitlle,reviewText,rate,reviewTime) VALUES('".$rid."', '".$uname."', '".$reviewtitle."', '".$comment."','".$rate."',NOW())")){
    		    $successmsg1 = "Successful";
        	} else {
          		$errormsg1 = "Error in commenting...Please try again later!";
        	}
        }

   		
	}
	
	

	if (isset($_POST['submit3'])) {
		$error2 = false;
    	
		if(!$_POST['advice']) {
        	$error2 = true;
        	$adviceError = "advice should not be empty";
   		 }
   		 
    	if (!$error2){
    		$advice = mysqli_real_escape_string($con, $_POST['advice']); 
    	 	if(mysqli_query($con, "INSERT INTO RecipeAdvice (rid,uname, advice, atime) VALUES('".$rid."', '".$uname."', '".$advice."',NOW())")){
            	$successmsg2 = "Successful</a>";
        	} else {
          		$errormsg2 = "Error in suggesting...Please try again later!";
        	}
        }
	}
	
	if (isset($_POST["comeback"])){
	header("Location: recipe.php?rid =$rid");
	}	

	?>
<!DOCTYPE html>
<html>
<head>
    <title>Comment | Cookzila</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- 
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
 -->
    
     <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Cookzila</a>
        </div>
        <div class="pull-right" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['uname'])) { ?>
                <li><p class="navbar-text">Hello <a href="UserInfo.php"><?php echo $_SESSION['uname']."!"; ?></a></p>
                <li><p class="navbar-text">Not  <?php echo $_SESSION['uname']."? "; ?><a href="login.php">Login</a></p></li>
                <li><a href="logout.php">Log Out</a></li>
                <?php }  ?>
            </ul>
        </div>
    </div>
</nav>
	<h4 align="center">Recipe Review </h4>
	<div class="container">
    	<div class="row">
        	<div class="col-md-4 col-md-offset-4 well">
				<form action="" method="POST" enctype="multipart/form-data">
				<label for="name">Upload your cuisine image using this recipe</label>
    			<input type="file" name="file"><br><br>
    			<span class="text-danger"><?php if (isset($fileError)) echo $fileError."<br/>";?></span>
    			<input type="submit" name="submit" value="Submit">
				</form>
        		<span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
        		<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        	</div>
    	</div>
	</div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                <label for="name">Leave some comments</label>
                <div class="form-group">
                        <input type="text" name="reviewtitle" placeholder="Review Title" />
                        <span class="text-danger"><?php if (isset($titleError)) echo $titleError."<br/>";?>
                </div>
                <div class="form-group">
					<textarea class="form-control" name ="comment" placeholder = "Enter your comment"></textarea>
				</div>
				<div class="form-group">
                        <input type="text" name="rate" placeholder="Rate it (0-5) in stars " />
                        <span class="text-danger"><?php if (isset($rateError)) echo $rateError."<br/>";?>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit2" value="Submit" class="btn btn-primary" />
                </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg1)) { echo $successmsg1; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg1)) { echo $errormsg1; } ?></span>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                 <label for="advice">Provide some suggestions to make it better</label>
				<div class="form-group">
					<textarea class="form-control" name ="advice" placeholder = "Provide some suggestions"></textarea>
					     <span class="text-danger"><?php if (isset($adviceError)) echo $adviceError."<br/>";?>

				</div>
                <div class="form-group">
                    <input type="submit" name="submit3" value="Submit" class="btn btn-primary" />
                </div>
                <div class="form-group">
             	    <input type="submit" name="comeback" value ="Come Back" class="btn btn-success btn-lg" />
          	    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg2)) { echo $successmsg2; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg2)) { echo $errormsg2; } ?></span>
        </div>
    </div>
</div>
</body>
</html>

