<?php
session_start();
include_once 'dbconnect.php'; //include php file
	

if (isset($_SESSION['uname']) && isset($_SESSION['upassword'])) {
    $name = mysqli_real_escape_string($con, $_SESSION["uname"]); //escape all the characters that could damange database
	$result0 = mysqli_query($con, "SELECT * FROM Uprofile WHERE uname = '" . $name. "'");
	$row0 = mysqli_fetch_array($result0);
// 	print_r($row0);
    if ($row0) {
    		
    } else {
    	$errormsg = "You have not edit a profile yet! Please introduce yourself!";
    }
} else {
  header("Location: login.php");
  }
	//get the new group id
	$_SESSION['CREATED'] = time();
	if(!isset($_SESSION['gid'])){	
		$r = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'try' AND   TABLE_NAME   = 'CookingGroup'";
		if($result=mysqli_query($con, $r)){
			$rowr = mysqli_fetch_assoc($result);
			$_SESSION['gid'] = $rowr['AUTO_INCREMENT'];
		}
	} else  if (time() - $_SESSION['CREATED'] > 600) {
    	session_regenerate_id(true);    
    	$_SESSION['CREATED'] = time();  
    }
    //create group
	$error1 = false;
	if (isset($_POST['submit1'])) {
		if(!$_POST['gname']){
			$error1 = true;
			$titleError="Please enter your group name";
		}
		
   		 if(!$_POST['desc']) {
        	$error1 = true;
        	$descError = "General information should not be empty";
   		 }
   		 
    	 if (!$error1){
    	 	$gname = mysqli_real_escape_string($con, $_POST['gname']); 
    		$gDesc = mysqli_real_escape_string($con, $_POST['desc']); 
    		if(mysqli_query($con, "INSERT INTO CookingGroup(gname,gManager,gDesc) VALUES('".$gname."', '".$name."', '".$gDesc."')")){
    		    $successmsg1 = "Successful";
        	} else {
          		$errormsg1 = "Error in creating...Please try again later!";
        	}
        }
   		
	}
	// my group
	$query='SELECT  distinct *  FROM CookingGroup WHERE gManager = "'.$name.'"' ;
	$result = mysqli_query($con,$query);
	if ($result) $num = mysqli_num_rows($result); 
	else { echo "false query";}
	if($num != 0)  {
		while($row = mysqli_fetch_assoc($result)){
    		$a[] = $row;
		}
	} else {
		$a[] = array('Iname '=>' ','Iqty'=>' ','Unit'=>' ') ;
	}
// 	print_r($a);
	//joined group
	$query1='SELECT distinct * FROM  Member natural join CookingGroup where uname = "'.$name.'"';
	$result1 = mysqli_query($con,$query1);
	if ($result1) $num1 = mysqli_num_rows($result1); 
	else { echo "false query";}
	if($num1 != 0)  {
		while($row1 = mysqli_fetch_assoc($result1)){
    		$a1[] = $row1;
		}
	} else {
		$a1[] = array('Iname '=>' ','Iqty'=>' ','Unit'=>' ') ;
	}
	
	
	//rsvped events
	$querymt= "SELECT * FROM Meeting natural join JoinRSVP WHERE uname ='".$name."' order by mTime DESC"; //latest two weeks
// 	echo $querymt;
	$resultmt = mysqli_query($con,$querymt);
	if ($resultmt) $nummt0 = mysqli_num_rows($resultmt); 
	else { echo "false query";}
	if($nummt0 != 0) {
		while($rowmt0 = mysqli_fetch_assoc($resultmt)){
    		$amt0[] = $rowmt0;
		}
	} 
// 	print_r($amt);


	//recent events
	$querymt= "select distinct * from meeting where mid in (select distinct mid from member natural join CookingGroup natural join holdMeeting where gManager  ='".$name."' or uname = '".$name."') and  mTime >= DATE_ADD(CURDATE(), INTERVAL 1 DAY) and mTime <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)"; //latest two weeks
// 	echo $querymt;
	$resultmt = mysqli_query($con,$querymt);
	if ($resultmt) $nummt = mysqli_num_rows($resultmt); 
	else { echo "false query";}
	if($nummt != 0) {
		while($rowmt = mysqli_fetch_assoc($resultmt)){
    		$amt[] = $rowmt;
		}
	} 
// 	print_r($amt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>UserInfo | Cookzila</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />  
     <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

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
                <li><p class="navbar-text">Hello <?php echo $_SESSION['uname']."!"; ?></p></li>
                <li><p class="navbar-text">Not  <?php echo $_SESSION['uname']."? "; ?><a href="login.php">Login</a></p></li>
                <li><a href="logout.php">Log Out</a></li>
                <?php }  ?>
            </ul>
        </div>
    </div>
</nav>
<!-- 	<?php if (isset($errormsg)) {echo $errormsg;} else {echo Hello;}?>  -->
    <h3> Welcome to <?php echo $name."'s kitchen!"?> </h3> 
    <hr /> 
    <p> <?php if (isset($errormsg)) {echo $errormsg;}?></p>
     
    <h4><?php echo $name."'s"?> profile</h4>
    <?php if (isset($errormsg) or $row0['profile']=='null') {
    		echo "<p> it's a secret.ho ho ho...</p>";
    	 } else {
    	 echo $row0['profile'];}?>
     <p><a href="EditProfile.php"> [edit your profile]</a> </p>
     
    <hr />   
    <h4 align="center"> <?php echo $name."'s"?> favorite recipes</a></h4> 
<?php
 	$query1="SELECT * FROM Recipe NATURAL JOIN favRecipe WHERE fname ='".$name."'" ;
//  	echo $query1;
	$result = mysqli_query($con,$query1);
	if ($result){
	$resultsr1 = mysqli_num_rows($result); 
	$s='<p align="center">';
	if ($resultsr1>0) {
		$colNum = 3; $i = 0;
   		while($row = $result->fetch_assoc()) {
   			$i = $i+1;
   			$rid = $row["rid"];
			$rtitle = $row["rtitle"];
			$rServNum= $row["rServNum"];
			$rCookTime= $row["rCookTime"];
			$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 3");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			$s.= '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." serving No.: </b>" . $rServNum. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>" .$iId . "<br><b>cook time: </b>" . $rCookTime. " minutes <br>";
		}
	}
    echo $s.'</div>';}?>
    
    
    
<!--     <h4> Recipes <?php echo $name;?>  might like </a> </h4> -->
    
    
  
    <h4 align="center"> <?php echo $name."'s"?> recipes </a> </h4>
<?php
// 	$query="SELECT * FROM Recipe join RecipeTag natural join Tag WHERE tname ='".$tname."'" ;
	$query2=" SELECT * FROM recipe natural join User WHERE uname ='".$name."' order by rTime DESC" ;
	$result = mysqli_query($con,$query2);
	$results = mysqli_num_rows($result); 
	$s="<p align='center'>";
   	if ($results>0) {
   		while($row = $result->fetch_assoc()) {
   			$rid = $row["rid"];
			$rtitle = $row["rtitle"];
			$rServNum= $row["rServNum"];
			$rCookTime= $row["rCookTime"];
			$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 1");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			$s.= '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." serving No.: </b>" . $rServNum. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>" .$iId . "<br><b>cook time: </b>" . $rCookTime. " minutes <br>";
		}
	}
    echo $s;   	
?>
      <h4 align="center"> Recipes <?php echo $name;?> visited recently </a> </h4>
<?php
// 	$query="SELECT * FROM Recipe join RecipeTag natural join Tag WHERE tname ='".$tname."'" ;
	$query2=" select distinct rid, rtitle, rServNum, rCookTime from (SELECT * FROM recipe natural join readRecipe WHERE rname ='".$name."' order by logtime DESC ) as a limit 5" ;
// 	echo $query2;
	$result = mysqli_query($con,$query2);
	$results = mysqli_num_rows($result); 
	$s="<p align='center'>";
   	if ($results>0) {
   		while($row = $result->fetch_assoc()) {
   			$rid = $row["rid"];
			$rtitle = $row["rtitle"];
			$rServNum= $row["rServNum"];
			$rCookTime= $row["rCookTime"];
			$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 1");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			$s.= '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." serving No.: </b>" . $rServNum. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>" .$iId . "<br><b>cook time: </b>" . $rCookTime. " minutes <br>";
		}
	}
    echo $s;   	
?>    
    
    <hr /> 
	<h4>Want to add a recipe?</h4>
    <form action = "addRecipe.php">
    <input type="submit" name = "clickhere" value="Click Here!"/>
    </form> </div> 
    
    <hr/>
    <h4> <?php echo $name."'s"?> group</h4> 
       <table width="100%">
		<tr>
		<?php
    	$rowCount = 0;
    	$max_recipe = $num;
  		$max_column = 3;
  		if ($max_recipe % $max_column == 0) {
  			$max_per_column=$max_recipe/$max_column;
  		} else {
  			$max_per_column= ($max_recipe/$max_column) +1;
  		}
    	foreach($a as $key => $value) if ($rowCount < $max_recipe) { ?>
   		<?php if($rowCount % $max_per_column == 0) {  ?>
     	<td><table>
   		<?php } ?>                   
       	<ul>
        	 <li><?php echo '<a href="group.php?gid=' . $value['gid'] . '">' .$value['gname']. '</a>'."<br>";  ?></li> 
        	 
       </ul>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   	<?php } ?>                  
	<?php $rowCount++; } ?>
	</tr>
	</table>
	
	<hr /> 
	<h4> <?php echo $name." joined"?> group</h4> 
       <table width="100%">
		<tr>
		<?php
    	$rowCount = 0;
    	$max_recipe = $num1;
  		$max_recipe1 = 3;
  		if ($max_recipe % $max_recipe1 == 0) {
  			$max_per_column=$max_recipe/$max_recipe1;
  		} else {
  			$max_per_column= ($max_recipe/$max_recipe1) +1;
  		}
  		if ($max_per_column == 0){
  			$max_per_column=1;
  		}
    	foreach($a1 as $key => $value) if ($rowCount < $max_recipe) { ?>
   		<?php if($rowCount % $max_per_column == 0) {  ?>
     	<td><table>
   		<?php } ?>                   
       	<ul>
        	 <li><?php echo '<a href="group.php?gid=' . $value['gid'] . '">' .$value['gname']. '</a>'."<br>";  ?></li> 
        	 
       </ul>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
     
   	<?php } ?>                  
	<?php $rowCount++; } ?>
	</tr>
	</table>
	
	
	<hr /> 
	<h4> <?php echo $name." RSVPed"?> Event</h4> 
       <table width="100%">
		<tr>
		<?php
    	$rowCount = 0;
    	$max_recipe0 = $nummt0;
  		if($nummt0 != 0){
  		$max_recipe1 = 3;
  		if ($max_recipe0 % $max_recipe1 == 0) {
  			$max_per_column=$max_recipe0/$max_recipe1;
  		} else {
  			$max_per_column= ($max_recipe0/$max_recipe1) +1;
  		}
  		if ($max_per_column == 0){
  			$max_per_column=1;
  		}
    	foreach($amt0 as $key => $value) if ($rowCount < $max_recipe0) { ?>
   		<?php if($rowCount % $max_per_column == 0) {  ?>
     	<td><table>
   		<?php } //GET THE LEFT TIME
   		$qu="SELECT TIMEDIFF('".$value['mTime']."', NOW()) as le" ;
//    		echo $qu;
		$result = mysqli_query($con,$qu);
		if ($result){ 
			$num = mysqli_num_rows($result); 
			if($num != 0) {
				$row = mysqli_fetch_assoc($result);
				$diffarr = explode( ':', $row['le']);   
				if($diffarr[0]>0) {
				 $timeleft = "with ".$diffarr[0].":".$diffarr[1].":".$diffarr[2]." left";
				} else {
				$timeleft ='';
				}
			}
		}else { echo "false query";}?>
                                    
       	<table>
         		<tr><th><?php echo $value['mname']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>".'<a href="meeting.php?mid=' . $value['mid'] . '">Info</a>'."</th></tr>".
         	  "<tr><td>".$value['mTime']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>".$value['mPlace']."<br>".$timeleft."</td><tr> <br>"; ?>
       	</table>
        	 
       
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
     
   	<?php } ?>                  
	<?php $rowCount++;} } ?>
	</tr>
	</table>

</hr>
	
	<h3> Recent events </a> </h4>
	<table>
<?php if ($nummt == 0){?>
 <ul>
    <li> No events are added yet.</li>
 </ul>
<?php }else{
    $rowCount = 0;
    $max_recipeM = $nummt;
  	$max_column = 2;
  	if ($max_recipeM % $max_column == 0) {
  		$max_per_column=$max_recipeM/$max_column;
  } else {
  		$max_per_column=$max_recipeM/$max_column +1;
  }
    foreach($amt as $key => $value) if ($rowCount < $max_recipeM) { ?>
   <?php if($rowCount % $max_per_column == 0) {  ?>
     <td><table>
   <?php }  
        //GET THE LEFT TIME
   		$qu="SELECT TIMEDIFF('".$value['mTime']."', NOW()) as le" ;
//    		echo $qu;
		$result = mysqli_query($con,$qu);
		if ($result){ 
			$num = mysqli_num_rows($result); 
			if($num != 0) {
				$row = mysqli_fetch_assoc($result);
				$diffarr = explode( ':', $row['le']);   
				if($diffarr[0]>0) {
				 $timeleft = "with ".$diffarr[0].":".$diffarr[1].":".$diffarr[2]." left";
				} else {
				$timeleft ='';
				}
			}
		}else { echo "false query";}?>
                 
         <table>
         		<tr><th><?php echo $value['mname']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>".'<a href="meeting.php?mid=' . $value['mid'] . '">RSVP</a>'."</th></tr>".
         	  "<tr><td>".$value['mTime']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>".$value['mPlace']."<br>".$timeleft."</td><tr> <br>"; ?>
       	</table>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   <?php } ?>                  
<?php $rowCount++; }} ?>
</tr>
</table>
</table>
     
    <!-- 
<img src="https://media3.giphy.com/media/vY7iEc5AUbyr6/200w.gif" height = 300/>
    <img src="https://media0.giphy.com/media/Byp2MtxE5Tyla/200w.gif" height = 300/>
   
 -->
    <hr /> 
	 <h4 align="center"> Want to create a group?</h4>
  <div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                <label for="name">Group Info</label>
                <div class="form-group">
                        <input type="text" name="gname" placeholder="Group Name" />
                        <span class="text-danger"><?php if (isset($titleError)) echo "<br>".$titleError."<br/>";?>
                </div>
                <div class="form-group">
					<textarea class="form-control" name ="desc" placeholder = "General description"></textarea>
					<span class="text-danger"><?php if (isset($descError)) echo $descError."<br/>";?>
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
</body>
</html>