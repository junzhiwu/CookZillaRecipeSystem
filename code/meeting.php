<?php
	session_start();
	include_once 'dbconnect.php';
	?>
<!DOCTYPE html>
<html>
<head>
    <title>Home | Cookzila</title>
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
<!-- login setting -->
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php"> Welcome to Cookzila!</a>
        </div>
        <div class="pull-right" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['uname'])) { ?>
                <li><p class="navbar-text">Hello <a href="UserInfo.php"><?php echo $_SESSION['uname']."!"; ?></a></p></li>
                <li><p class="navbar-text">Not  <?php echo $_SESSION['uname']."? "; ?><a href="login.php">Login</a></p></li>
                <li><a href="logout.php">Log Out</a></li>
                <?php } else { ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Sign Up</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<?php
if(isset($_GET['mid'])) {
	$_SESSION['mid'] = $_GET['mid'];
} 
if (!isset($_SESSION['mid'])){
	echo '<p> Please select meeting in <a href="group.php"> your group</a></p>';
} else {
	$mid = $_SESSION['mid'];
	if(!isset($_SESSION['uname'])) {
    	header("Location: login.php"); 
    } else {
    	$name = $_SESSION['uname'];
    	$re1 = mysqli_query($con, "SELECT * FROM Member where uname = '".$name."' and gid = (SELECT distinct gid FROM CookingGroup natural join holdMeeting natural join Meeting where mid ='".$mid."')");
    	$re = mysqli_num_rows($re1);
    	$ren1 = mysqli_query($con, "SELECT * FROM CookingGroup where gManager = '".$name."' and gid = (SELECT distinct gid FROM CookingGroup natural join holdMeeting natural join Meeting where mid ='".$mid."')");
//     	echo "SELECT * FROM CookingGroup where gManager = '".$name."' and gid = (SELECT distinct gid FROM CookingGroup natural join holdMeeting natural join Meeting where mid ='".$mid."')";
    	$ren = mysqli_num_rows($ren1);
//     	echo $re;
    	if($re== 0 and $ren == 0){
        	header("Location: group.php"); 
   		} else {
// 	$keyword =$_SESSION['rkeyword'];
	
// 	$uname = $_SESSION['uname'];
	$mid = $_SESSION['mid'];
	
	//GET Meeting info
	$query="SELECT * FROM Meeting natural join holdMeeting natural join cookingGroup WHERE mid = $mid" ;
	$result = mysqli_query($con,$query);
	if ($result) $num = mysqli_num_rows($result); 
	else { echo "false query";}
	if($num != 0) $row = mysqli_fetch_assoc($result);
	
	//get current time
	$query1="SELECT NOW()as cTime" ;
	$result1 = mysqli_query($con,$query1);
	if ($result1) $num1 = mysqli_num_rows($result1); 
	else { echo "false query";}
	if($num1 != 0) $row1 = mysqli_fetch_assoc($result1);
// get event report
	$query3="SELECT * FROM MeetingReports WHERE mid = $mid" ;
// 	echo $query3;
	$result3 = mysqli_query($con,$query3);
	if ($result3) $num3 = mysqli_num_rows($result3); 
	else { echo "false query";}
	if($num3 != 0) {
		while($row3 = mysqli_fetch_assoc($result3)){
    		$a3[] = $row3;
		}
	} else {
		$a3 = array('Iname '=>' ','Iqty'=>' ','Unit'=>' ') ;
	}		
// 	print_r($a3);


$error1 = false;
	if (isset($_POST['submit1'])) {
   		 if(!$_POST['steps']) {
        	$error1 = true;
        	$stepsError = "steps should not be empty";
   		 }
   		 
    	 if (!$error1){
    		$mReport = mysqli_real_escape_string($con, $_POST['steps']); // true name
//     		echo "INSERT INTO MeetingReports(mid,rpname,report) VALUES('".$mid."','".$name."','".$mReport."')";
    		if(mysqli_query($con, "INSERT INTO MeetingReports(mid,rpname,report) VALUES('".$mid."','".$name."','".$mReport."')")){
    		    $successmsg1 = "Successful";
        	} else {
          		$errormsg1 = "Error in commenting...Please try again later!";
        	}
        }
   		
	}
	
	
	
	if(isset($_POST['submit3'])){// 
		if (isset($_FILES["file"]["name"])) {

    	$iname = $_FILES["file"]["name"];
    	$tmp_name = $_FILES['file']['tmp_name'];
    	$error = $_FILES['file']['error'];

    		if (!empty($iname)) {
        		$location = 'Images/';
				$TargetPath=time().$iname;
// 				$TargetPath=$iname;				
        		if(move_uploaded_file($tmp_name, $location.$TargetPath)){
//             		echo 'Uploaded';
//             		echo " INSERT INTO MeetingImg (mid, ipname, IDir) VALUES ('".$mid."','".$name."', '".$TargetPath."')";
    				if(mysqli_query($con, "INSERT INTO MeetingImg (mid, ipname, IDir) VALUES ('".$mid."','".$name."', '".$TargetPath."')")){
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
?>

<?php 
//display image
  $sql = "SELECT * FROM MeetingImg WHERE mid=$mid and ipname = '".$name."' limit 4";
//   echo $sql;
  $resulti = mysqli_query($con,$sql);
  if ($resulti) $numi = mysqli_num_rows($resulti); 
  else { echo "false query";}
  if($numi != 0) {
	while($rowi = mysqli_fetch_assoc($resulti)){
    	$ai[] = $rowi;
	}
  } else {
	$ai = array(' '=>' ') ;
//   	echo "no result";
  }
//   print_r($a);
?>	
	

<!-- rate -->
<h4 align="center"> <?php echo $row['mname']."<em> hold by ".$row['gname']." group</em>"?></h4> 

<h4> Event Description:</h4>
<h5><?php echo nl2br($row['mDesc'])?></h5>
<!-- <a name="joinGroup" /> -->
<?php
//RSVP
	$error0 = false;
	if (isset($_POST['join'])) {
// 		echo "click";
		if($row1['cTime']>$row['mTime']){
        	$error0 = true;
    		$errorm0 = "Time expired";
   		}else {
//     		$name = $_SESSION['uname'];
    		$re1 = mysqli_query($con, "SELECT * FROM Meeting WHERE mid ='".$mid."'and pname = '".$name."'");
    		$re2 = mysqli_query($con, "SELECT * FROM JoinRSVP WHERE mid ='".$mid."'and uname = '".$name."'");
//     		$re = mysqli_num_rows($re1) + mysqli_num_rows($re2);
//     		echo $re;
    		if(mysqli_num_rows($re1)!=0 or mysqli_num_rows($re2)!=0){
        		$error0 = true;
    			$errorm0 = "Already RSVPed";
   			 }
   		 }
    	if (!$error0){
    		$jv =mysqli_query($con, "INSERT INTO JoinRSVP(uname,mid) VALUES('".$name."', '".$mid."')");
    		if($jm){
    		    $successmsg0 = "Successful";
        	} else {
          		$errormsg0 = "Error in creating...Please try again later!";
        	}
        }
   		
	}
?>

<form role="form"  method="post" name="signupform">
	<div class="form-group">
  		<input type='submit' name = 'join' value='RSVP Now'/>
	</div>
</form>
<span class="text-success"><?php if (isset($successmsg0)) { echo $successmsg0; } ?></span>
<span class="text-danger"><?php if (isset($errorm0)) { echo $errorm0; } ?><br><?php if (isset($errormsg0)) { echo $errormsg0; } ?></span>


<h5> <?php echo "Event time: ".$row['mTime']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo "Event place: ".$row['mPlace']?></h5>
<hr />

<h4 align="center">Event Images</h4>
<?php if ($numi == 0){?>
 <ul>
    <li> No images are added yet.</li>
 </ul>
<?php } else{
	$rowCount = 0;
    $max_recipei = $numi;
	$max_column = 2;
  if ($max_recipei % $max_column == 0) {
  $max_per_column=$max_recipei/$max_column;
  } else {
  $max_per_column=$max_recipei/$max_column +1;
  }
    foreach($ai as $key => $value) if ($rowCount < $max_recipei) { $MyPhoto=  $value['IDir'];?>
   	<?php if($rowCount % $max_per_column == 0) {  ?>
    <td><table>
    <?php } ?>                   
    <ul>
         <li><?php echo "by ".$value['ipname']."  <br> <br>"; ?><?php echo "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'height = 200/>";?></li>
  </ul>
    <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
    </table></td> 
   	<?php } ?>                  
	<?php $rowCount++; }}?>
 <br><br>

<hr />





<h4 align="center">Event reports</h4>
<table width="100%">
<tr>
<?php
    $rowCount = 0;
    $max_recipe3 = $num3;
  	$max_column = 2;
  	if ($max_recipe3 % $max_column == 0) {
  		$max_per_column=$max_recipe3/$max_column;
  } else {
  		$max_per_column=$max_recipe3/$max_column +1;
  }
    foreach($a3 as $key => $value) if ($rowCount < $max_recipe3) { ?>
   <?php if($rowCount % $max_per_column == 0) {  ?>
     <td><table>
   <?php } ?>                   
         <ul>
         <li> <?php echo $value['report']."</li>"; ?>By <?php echo $name;?></li>
       <ul>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   <?php } ?>                  
<?php $rowCount++; } ?>
</tr>
</table>

<?php if ($num3 == 0){?>
 <ul>
    <li> No reports are added yet.</li>
 </ul>
<?php }?>
<hr />

 

<h4 align="center"> New Report </h4>
	
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <div class="form-group">
					<textarea class="form-control" name ="steps" placeholder = "Event report"></textarea>
					<span class="text-danger"><?php if (isset($stepsError)) echo $stepsError."<br/>";?>
					<div class="form-group">
                    <input type="submit" name="submit1" value="Submit" class="btn btn-primary" />
                </div>
				</div>
            </form>
            <span class="text-success"><?php if (isset($successmsg1)) { echo $successmsg1; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg1)) { echo $errormsg1; } ?></span>
        </div>
    </div>
</div>

<div class="container">
    	<div class="row">
        	<div class="col-md-4 col-md-offset-4 well">
				<form action="" method="POST" enctype="multipart/form-data">
				<label for="name">Upload Event Images</label>
    			<input type="file" name="file"><br><br>
    			<span class="text-danger"><?php if (isset($fileError)) echo $fileError."<br/>";?></span>
    			<input type="submit" name="submit3" value="Submit">
				</form>
        		<span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
        		<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        	</div>
    	</div>
	</div>
</body>
</html>
<?php 
}//already join the group
}//already login 
}//already known mid ?>