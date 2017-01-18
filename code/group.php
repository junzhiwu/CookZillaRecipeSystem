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
// 	$keyword =$_SESSION['rkeyword'];
	if(isset($_GET['gid'])) {
		$_SESSION['gid'] = $_GET['gid'];
	} 
	if (!isset($_SESSION['gid'])){
		echo '<p> Please select group in <a href="index.php">home page</a></p>';
	} else {
// 	$uname = $_SESSION['uname'];
	$gid = $_SESSION['gid'];
	
	
	$query="SELECT * FROM CookingGroup WHERE gid = $gid" ;
	$result = mysqli_query($con,$query);
	if ($result) $num = mysqli_num_rows($result); 
	else { echo "false query";}
	if($num != 0) $row = mysqli_fetch_assoc($result);

// get meetings
	$query3="SELECT * FROM CookingGroup natural join holdMeeting  natural join Meeting WHERE gid = $gid" ;
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
		

?>

	
	

<!-- rate -->
<h4 align="center"> <?php echo $row['gname']."<em> created by ".$row['gManager']."</em>"?></h4> 


<h4> Group Description:</h4>
<h5><?php echo nl2br($row['gDesc'])?></h5>
<!-- <a name="joinGroup" /> -->
<?php
//join group
	$error0 = false;
	if (isset($_POST['join'])) {
// 		echo "click";
		if(!isset($_SESSION['uname'])) {
    		$error0 = true;
    		$errorm0 = "Please first login"; 
    	} else {
    		$name = $_SESSION['uname'];
    		$re1 = mysqli_query($con, "SELECT * FROM Member WHERE gid ='".$gid."'and uname = '".$name."'");
    		$re2 = mysqli_query($con, "SELECT * FROM CookingGroup WHERE gid ='".$gid."'and gManager = '".$name."'");
    		$re = mysqli_num_rows($re1) + mysqli_num_rows($re2);
//     		echo $re;
    		if($re!= 0){
        		$error0 = true;
    			$errorm0 = "Already joined";
   			 }
   		 }
    	 if (!$error0){
    		$jm =mysqli_query($con, "INSERT INTO Member(gid,uname,joinTime) VALUES('".$gid."', '".$name."',NOW())");
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
  		<input type='submit' name = 'join' value='Join this group'/>
	</div>
</form>
<span class="text-success"><?php if (isset($successmsg0)) { echo $successmsg0; } ?></span>
<span class="text-danger"><?php if (isset($errorm0)) { echo $errorm0; } ?><br><?php if (isset($errormsg0)) { echo $errormsg0; } ?></span>
<hr />
<!-- <h5> <?php echo "Cooking time: ".$row['rCookTime']."minutes"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo "Serving number: ".$row['rServNum']?></h5>-->
<h4 align="center">Events hold by  <?php echo $row['gname'];?></h4>
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
         <table>
         		<tr><th><?php echo $value['mname']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>".'<a href="meeting.php?mid=' . $value['mid'] . '">RSVP</a>'."</th></tr>".
         	  "<tr><td>".$value['mTime']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>".$value['mPlace']."</td><tr> <br>"; ?>
       	</table>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   <?php } ?>                  
<?php $rowCount++; } ?>
</tr>
</table>

<?php if ($num3 == 0){?>
 <ul>
    <li> No events are added yet.</li>
 </ul>
<?php }?>
<hr />

<?php
	//create meetings
	$error1 = false;
	if (isset($_POST['submit1'])) {
		if(!isset($_SESSION['uname'])) {
    		$error1 = true;
    		$errorm12 = "Please first login"; 
    	} else {
    		$name = $_SESSION['uname'];
    		$re1 = mysqli_query($con, "SELECT * FROM Member WHERE gid ='".$gid."'and uname = '".$name."'");
    		$re2 = mysqli_query($con, "SELECT * FROM CookingGroup WHERE gid ='".$gid."'and gManager = '".$name."'");
    		$re = mysqli_num_rows($re1) + mysqli_num_rows($re2);
//     		echo $re;
    		if($re== 0){
    		    $error1 = true;
    			$errorm = "Please first join the group";
        	} else {
        		
				if(!$_POST['mname']){
					$error1 = true;
					$titleError="Please enter your event name";
				}
		
   		 		if(!$_POST['desc']) {
        			$error1 = true;
        			$descError = "General information should not be empty";
   				 }
   				 if(!$_POST['time']) {
        			$error1 = true;
        			$timeError = "Please set your event time";
   				 }
   			 	if(!$_POST['place']) {
        			$error1 = true;
        			$placeError = "Please enter your even place";
   				 }
   			 }
   		 }
    	 if (!$error1){
    	 	$mname = mysqli_real_escape_string($con, $_POST['mname']); 
    		$mDesc = mysqli_real_escape_string($con, $_POST['desc']);
    		$mTime = mysqli_real_escape_string($con, $_POST['time']); 
    		$mPlace = mysqli_real_escape_string($con, $_POST['place']);  
    		$cm =mysqli_query($con, "INSERT INTO Meeting(pname,mname,mDesc,mTime,mPlace) VALUES('".$name."', '".$mname."', '".$mDesc."', '".$mTime."', '".$mPlace."')");
    		if ($cm) {
    			$rr = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'try' AND   TABLE_NAME   = 'Meeting'";
				if($result=mysqli_query($con, $rr)){
					$rowr = mysqli_fetch_assoc($result);
					$mid = $rowr['AUTO_INCREMENT']-1;
				}	
    			$gm =mysqli_query($con, "INSERT INTO holdMeeting(gid,mid) VALUES('".$gid."', '".$mid."')");
    			if($gm){
    		  	  $successmsg1 = "Successful";
    		  	}
        	} else {
          		$errormsg1 = "Error in creating...Please try again later!";
        	}
        }
   	header("location: group.php");	
	}
?>
	 <h4 align="center"> Want to organize a event?</h4>
  <div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                <label for="name">Event Info</label>
                <div class="form-group">
                        <input type="text" name="mname" placeholder="Event Name" />
                        <span class="text-danger"><?php if (isset($titleError)) echo "<br>".$titleError."<br/>";?>
                </div>
                <div class="form-group">
					<textarea class="form-control" name ="desc" placeholder = "General description"></textarea>
					<span class="text-danger"><?php if (isset($descError)) echo $descError."<br/>";?>
				</div>
				<div class="form-group">
                        <input type="text" name="time" placeholder="Time (yyyy-mm-rr hh:mm:ss)" size="31" />
                        <span class="text-danger"><?php if (isset($timeError)) echo "<br>".$timeError."<br/>";?>
                </div>
                <div class="form-group">
                        <input type="text" name="place" placeholder="Event Place" />
                        <span class="text-danger"><?php if (isset($placeError)) echo "<br>".$placeError."<br/>";?>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit1" value="Submit" class="btn btn-primary" />
                </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg1)) { echo $successmsg1; } ?></span>
             <span class="text-danger"><?php if (isset($errorm)) { echo $errorm; } ?><br>
             <?php if (isset($errorm12)) {echo $errorm12; }?><!--  <p><a href="#joinGroup">join the group</a></p>  -->
             <br><?php if (isset($errormsg1)) { echo $errormsg1; } ?></span>
           
        </div>
    </div>
</div>

<!-- 
<h5> <?php echo "Related recipes:<br>"?> </h5>     
<table width="100%">
<tr>
<?php
	if($num6 == 0) echo "No related recipe is found. Please find more in <a href='index.php'> home page.</a>";
	else { 
		while($row6 = mysqli_fetch_assoc($result6)){
    		$a6[] = $row; // Inside while loop
		}
    	$rowCount = 0;
    	$max_recipe6 = $num6;
  		$max_column = 2;
  		if ($max_recipe6 % $max_column == 0) {
  			$max_per_column=$max_recipe6/$max_column;
  		} else {
  			$max_per_column=$max_recipe6/$max_column +1;
  		}
    	foreach($a6 as $key => $value) if ($rowCount < $max_recipe6) { ?>
   		<?php if($rowCount % $max_per_column == 0) {  ?>
     	<td><table>
   		<?php } ?>                   
       	<tr>
         <td><?php echo '<a href="recipe.php?rid=' . $value['rid'] . '">' ."<b> * </b>" . $value['rtitle']. '</a>'." with serving number: ".$value['rServNum']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>"; ?><img src="https://media0.giphy.com/media/Byp2MtxE5Tyla/200w.gif" height = 300/><?php echo "<br> Cooking Time: ".$value['rCookTime']."minutes <br> <br>"; ?>
         </td>
       	</tr>
       	<?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     		</table></td> 
   		<?php } ?>                  
		<?php $rowCount++; } 
	}?>
</tr>
</table>
 -->

</body>
</html>
<?php }//already known gid ?>