<?php
	session_start();
	
	if(!isset($_SESSION['uname'])) {
   	 header("Location: login.php");
	}
	include_once 'dbconnect.php';
	$r = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'try' AND   TABLE_NAME   = 'Recipe'";
	if($result=mysqli_query($con, $r)){
		$rowr = mysqli_fetch_assoc($result);
		$_SESSION['rid'] = $rowr['AUTO_INCREMENT'];
	}
	//get the new recipe id
	// if(!isset($_SESSION['rid'])){
// 		$_SESSION['CREATED'] = time();
// 		$r = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'try' AND   TABLE_NAME   = 'Recipe'";
// 		if($result=mysqli_query($con, $r)){
// 			$rowr = mysqli_fetch_assoc($result);
// 			$_SESSION['rid'] = $rowr['AUTO_INCREMENT'];
// 		}
// 	} else  if (time() - $_SESSION['CREATED'] > 600) {
//     	session_regenerate_id(true);    
//     	$_SESSION['CREATED'] = time();  
//     }
// echo $_SESSION['rid'];
		

	$uname = $_SESSION['uname'];
	$rid = $_SESSION['rid'];
    	
	$error1 = false;
	if (isset($_POST['submit1'])) {
		if(!$_POST['recipetitle']){
			$error1 = true;
			$titleError="Please enter your recipe title";
		}
		if(!$_POST['rServNum']){
			$error1 = true;
			$rServNumError="Please enter the serving number";
		} else {
			if(!preg_match('/^[1-9][0-9]*$/', $_POST['rServNum'])) {
        		$error1 = true;
        		$rServNumError = "Serving number should be a number larger than 0";
   		 	}
   		 }
   		 if(!$_POST['steps']) {
        	$error1 = true;
        	$stepsError = "steps should not be empty";
   		 }
   		 if(!$_POST['tags']) {
        	$error2 = true;
        	$adviceError = "Tags should not be empty";
   		 }
   		 if(!$_POST['rCookTime']){
			$error1 = true;
			$rCookTimeError="Please enter the number of minutes";
		} else {
			if(!preg_match('/^[1-9][0-9]*$/', $_POST['rCookTime'])) {
        		$error1 = true;
        		$rCookTimeError = "cookTime should be a number larger than 0";
   		 	}
   		 }
		// if(!$_POST['tname']) {
//         	$error1 = true;
//         	$adviceError = "tags should not be empty";
//    		 }
    	 if (!$error1){
    	 	$rtitle = mysqli_real_escape_string($con, $_POST['recipetitle']); // user name
    		$rSteps = mysqli_real_escape_string($con, $_POST['steps']); // true name
    		$rCookTime = mysqli_real_escape_string($con, $_POST['rCookTime']);
    		$rServNum = mysqli_real_escape_string($con, $_POST['rServNum']);
    		if(mysqli_query($con, "INSERT INTO Recipe(uname,rtitle,rServNum, rCookTime,rSteps,rtime) VALUES('".$uname."', '".$rtitle."', '".$rServNum."', '".$rCookTime."','".$rSteps."',NOW())")){
    		    $tag_string = mysqli_real_escape_string($con, $_POST['tags']); 
    			$tags = explode(",", $tag_string );
    			$errorn = false;
    			for ($x = 0; $x < count($tags); $x++){
					//Due to unique it will only insert if the tag dosent already exist
					$tag = $tags[$x];
// 					echo $tag;
        			$r1 = mysqli_query($con, "INSERT INTO Tag (tname) VALUES('".$tag."')");
        			//Add the relational Link
        			$r2 = mysqli_query($con,"INSERT INTO RecipeTag VALUES('".$rid."', (SELECT tid FROM Tag WHERE Tag.tname = '".$tag."' limit 1))");
        			if(!$r2) $errorn= true;
   				}
    	 		if(!$errorn){
    		    	$successmsg1 = "Successful";
    		    }
        	} else {
          		$errormsg1 = "Error in commenting...Please try again later!";
        	}
        }
   		
	}
	
	
   
	// if (isset($_POST['submit2'])) {
// 		$error2 = false;
//     	
// 		if(!$_POST['tags']) {
//         	$error2 = true;
//         	$adviceError = "Tags should not be empty";
//    		 }
//    		 
//     	if (!$error2){
//     		$tag_string = mysqli_real_escape_string($con, $_POST['tags']); 
//     		$tags = explode(",", $tag_string );
//     		$errorn = false;
//     		for ($x = 0; $x < count($tags); $x++){
// 				//Due to unique it will only insert if the tag dosent already exist
// 				$tag = $tags[$x];
// 				echo $tag;
//         		$r1 = mysqli_query($con, "INSERT INTO Tag (tname) VALUES('".$tag."')");
//         		//Add the relational Link
//         		$r2 = mysqli_query($con,"INSERT INTO RecipeTag VALUES('".$rid."', (SELECT tid FROM Tag WHERE Tag.tname = '".$tag."' limit 1))");
//         		if($r2) echo true;
//    			}
//     	 	if(!$errorn){
//             	$successmsg2 = "Successful";
//         	} else {
//           		$errormsg2 = "Error in suggesting...Please try again later!";
//         	}
//         }
// 	}
	
	if(isset($_POST['submit3'])){// 
	    $rid = $rid - 1;
// 	    echo $rid ;
		$re1 = mysqli_query($con, "SELECT * FROM recipe WHERE rid ='".$rid."'");
    	$re = mysqli_num_rows($re1);
//     		echo $re;
    	if($re == 0){
    		$fileError = 'please first add a recipe';
   		} else {
			if (isset($_FILES["file"]["name"])) {

    		$name = $_FILES["file"]["name"];
    		$tmp_name = $_FILES['file']['tmp_name'];
    		$error = $_FILES['file']['error'];

    			if (!empty($name)) {
        			$location = 'Images/';
					$TargetPath=time().$name;
        			if(move_uploaded_file($tmp_name, $location.$TargetPath)){
//         	    		echo 'Uploaded';
    					if(mysqli_query($con, "INSERT INTO RecipeImg (rid, RecipeImgDir) VALUES ('".$rid."', '".$TargetPath."')")){
//     			    		echo "Successful";
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
	}
	?>
<!DOCTYPE html>
<html>
<head>
    <title>NewRecipe | Cookzila</title>
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
	<h4 align="center"> New Recipe </h4>
	
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                <label for="name">Recipe Info</label>
                <div class="form-group">
                        <input type="text" name="recipetitle" placeholder="Recipe Title" />
                        <span class="text-danger"><?php if (isset($titleError)) echo "<br>".$titleError."<br/>";?>
                </div>
                <div class="form-group">
					<textarea class="form-control" name ="steps" placeholder = "Steps description"></textarea>
					<span class="text-danger"><?php if (isset($stepsError)) echo $stepsError."<br/>";?>
				</div>
				<div class="form-group">
                        <input type="text" name="rServNum" placeholder="Serving number" />
                        <span class="text-danger"><?php if (isset($rServNumError)) echo  "<br>".$rServNumError."<br/>";?>

                </div>
                <div class="form-group">
                        <input type="text" name="rCookTime" placeholder="Cooking Time (minutes)" />
                        <span class="text-danger"><?php if (isset($rCookTimeError)) echo  "<br>".$rCookTimeError."<br/>";?>
                </div>
                 
                 <div class="form-group">
					<textarea class="form-control" name ="tags" placeholder = "add tags, seperated by commas"></textarea>
					     <span class="text-danger"><?php if (isset($adviceError)) echo $adviceError."<br/>";?>

				</div>
				
                <div class="form-group">
                    <input type="submit" name="submit1" value="Submit" class="btn btn-primary" />
                </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg1)) { echo $successmsg1; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg1)) { echo $errormsg1; } ?></span>
        </div>
    </div>
</div>
<!-- 
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                 <label for="tags">Attach tags</label>
				<div class="form-group">
					<textarea class="form-control" name ="tags" placeholder = "add tags, seperated by commas"></textarea>
					     <span class="text-danger"><?php if (isset($adviceError)) echo $adviceError."<br/>";?>

				</div>
                <div class="form-group">
                    <input type="submit" name="submit2" value="Submit" class="btn btn-primary" />
                </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg2)) { echo $successmsg2; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg2)) { echo $errormsg2; } ?></span>
        </div>
    </div>
</div>
 -->

<div class="container">
    	<div class="row">
        	<div class="col-md-4 col-md-offset-4 well">
				<form action="" method="POST" enctype="multipart/form-data">
				<label for="name">Upload your cuisine image using this recipe</label>
    			<input type="file" name="file"><br><br>
    			<span class="text-danger"><?php if (isset($fileError)) echo $fileError."<br/>";?></span>
    			<input type="submit" name="submit3" value="Submit">
				</form>
        		<span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
        		<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        	</div>
    	</div>
	</div>
	<h4 align="center">add ingredients</h4>
    <form align="center" action = "addIngredient.php">
    <input type="submit" value="Click Here!"/>
    </form>  
</body>
</html>


