
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
<?php
	session_start();
	include_once 'dbconnect.php';
// 	$keyword =$_SESSION['rkeyword'];
	if(isset($_SESSION['uname'])) $uname = $_SESSION['uname'];
	
	if(isset($_GET['rid'])) $_SESSION['rid'] = $_GET['rid'];
	$rid =  $_SESSION['rid'];
	
	$query="SELECT * FROM Recipe WHERE rid = $rid" ;
	$result = mysqli_query($con,$query);
	if ($result) $num = mysqli_num_rows($result); 
	else { echo "false query";}
	if($num != 0) $row = mysqli_fetch_assoc($result);
	
// 	get the tags
	$query1="SELECT tname FROM Tag natural join Recipe natural join RecipeTag  WHERE rid = $rid" ;
	$result1 = mysqli_query($con,$query1);
	if ($result1) $num1 = mysqli_num_rows($result1); 
	else { echo "false query";}
	if($num1 != 0) {
		$s1="<b>Tags: </b>";
		while($row1 = mysqli_fetch_assoc($result1)){
    		$tname = $row1["tname"];
			$s1.= '<a href="recipesByTag.php?tname=' . $tname . '">' . $tname. '</a>'.".  ";
		}
	} else {
		$s1 = "Tags not set yet";
	}
	
// get the average rate
	$query2="SELECT rid, round(AVG(rate),2) AS avgrate FROM Review GROUP BY rid HAVING rid = $rid" ;
	$result2 = mysqli_query($con,$query2);
	if ($result2) $num2 = mysqli_num_rows($result2); 
	else { echo "false query";}
	if($num2 != 0) {
		$row2 = mysqli_fetch_assoc($result2);
		$star = $row2['avgrate']. " star";
	} else {
		$star = "not rate yet";
	}
	
// get ingredients
	$query3="SELECT * FROM Ingredient WHERE rid = $rid" ;
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
	
// get reviews
	$query4="SELECT * FROM Review WHERE rid = $rid" ;
	$result4 = mysqli_query($con,$query4);
	if ($result4) $num4 = mysqli_num_rows($result4); 
	else { echo "false query";}
	if($num4 != 0) {
		while($row4 = mysqli_fetch_assoc($result4)){
    		$a4[] = $row4;
//     		print_r($a4);
		}
	} else {
		$a4 = array(' '=>' ') ;
	}
	
	$query5="SELECT * FROM Review WHERE rid = $rid" ;
	$result5 = mysqli_query($con,$query5);
	if ($result5) $num5 = mysqli_num_rows($result5); 
	else { echo "false query";}
	if($num5 != 0) {
		while($row5 = mysqli_fetch_assoc($result5)){
    		$a5[] = $row5;
//     		print_r($a4);
		}
	} else {
		$a5 = array(' '=>' ') ;
	}
	
	// get related recipes
	$query6="select * from recipe where rid in (
				select rid from Recipe natural join Tag natural join RecipeTag where tname in (
					select tname from Recipe natural join Tag natural join RecipeTag where rid = $rid) ) and rid <>$rid LIMIT 3" ;
// 	echo query6;
	$result6 = mysqli_query($con,$query6);
	if ($result6) {
		$num6 = mysqli_num_rows($result6);
	}else { echo "false query";}
	if($num6 != 0) {
		while($row6 = mysqli_fetch_assoc($result6)){
    		$a6[] = $row6;
//     		print_r($a4);
		}
	} else {
		$a6 = array(' '=>' ') ;
	}


//like it
	$error0 = false;
	if (isset($_POST['likeit'])) {
// 		echo "click";
		if(!isset($uname)){
        	$error0 = true;
    		$errorm0 = "Please frist log in";
   		}else {
//     		$name = $_SESSION['uname'];
    		$re1 = mysqli_query($con, "SELECT * FROM favRecipe WHERE rid ='".$rid."'and fname = '".$uname."'");
//     		$re = mysqli_num_rows($re1) + mysqli_num_rows($re2);
//     		echo $re;
    		if(mysqli_num_rows($re1)!=0){
        		$error0 = true;
    			$errorm0 = "Already bookmark as your favoriate recipes";
   			 }
   		 }
    	if (!$error0){
    		$jv =mysqli_query($con, "INSERT INTO favRecipe(rid,fname) VALUES('".$rid."', '".$uname."')");
    		if($jv){
    		    $successmsg0 = "Successful";
        	} else {
          		$errormsg0 = "Error in creating...Please try again later!";
        	}
        }
   		
	}
	
?>

<?php 
//display image
  $sql = "SELECT * FROM RecipeImg WHERE rid = $rid";
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

<?php 
//display image
  $sql2 = "SELECT * FROM ReviewImg WHERE rid=$rid";
//   echo $sql;
  $resulti2 = mysqli_query($con,$sql2);
  if ($resulti2) $numi2 = mysqli_num_rows($resulti2); 
  else { echo "false query";}
  if($numi2 != 0) {
	while($rowi2 = mysqli_fetch_assoc($resulti2)){
    	$ai2[] = $rowi2;
	}
  } else {
	$ai2 = array(' '=>' ') ;
//   	echo "no result";
  }
//   print_r($a);
?>	
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
<!-- rate -->
<h4> <?php echo $row['rtitle']."<em> by ".$row['uname']."</em>"?></h4> 
<h5> <?php echo $s1 ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo " Average rate: ".$star ?></h5>

<form role="form"  method="post" name="signupform">
	<div class="form-group">
  		<input type='submit' name = 'likeit' value='like it'/>
	</div>
</form>
<span class="text-success"><?php if (isset($successmsg0)) { echo $successmsg0; } ?></span>
<span class="text-danger"><?php if (isset($errorm0)) { echo $errorm0; } ?><br><?php if (isset($errormsg0)) { echo $errormsg0; } ?></span>
<hr /> 

<h5> <?php echo "Cooking time: ".$row['rCookTime']."minutes"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo "Serving number: ".$row['rServNum']?></h5>
<h5> Ingredients</h5>

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
         <li><?php echo $value['Iname'].": ".$value['Iqty']." ".$value['Unit']."<br>"; ?></li>
       </ul>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   <?php } ?>                  
<?php $rowCount++; } ?>
</tr>
</table>

<?php if ($num3 == 0){?>
 <ul>
    <li> No ingredients are added yet.</li>
 </ul>
<?php }?>
<hr />
<table>
<h5> Steps:</h5>
<p><?php echo nl2br($row['rSteps'])?></p>
</hr>

<table>
<h4>Recipe Images</h4>

<?php if ($numi == 0){?>
 <ul>
    <li> No recipe images are added yet.</li>
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
    foreach($ai as $key => $value) if ($rowCount < $max_recipei) { $MyPhoto=  $value['RecipeImgDir'];?>
   	<?php if($rowCount % $max_per_column == 0) {  ?>
    <td><table>
    <?php } ?>                   
    <ul>
         <li><?php echo "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'height = 200/>";?></li>
  </ul>
    <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
    </table></td> 
   	<?php } ?>                  
	<?php $rowCount++;} }?>
 <br><br>
</table>

<h5>Reviews</h5>
<table width="100%">
<tr>
<?php
    $rowCount = 0;
    $max_recipe4 = $num4;
  	$max_column = 2;
  	if ($max_recipe4 % $max_column == 0) {
  		$max_per_column=$max_recipe4/$max_column;
  } else {
  		$max_per_column=$max_recipe4/$max_column +1;
  }
    foreach($a4 as $key => $value) if ($rowCount < $max_recipe4) { ?>
   <?php if($rowCount % $max_per_column == 0) {  ?>
     <td><table>
   <?php } ?>                   
       <ul>
         <li><?php echo $value['reviewTitlle']?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $value['rate']." star <br>" ?>
         <?php echo nl2br($value['reviewText'])."<br>"; ?></li>
         <?php echo "by ".$value['rrname']?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo " at ".$value['reviewTime']."<br><br> "?>
         
       </ul>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   <?php } ?>                  
<?php $rowCount++; } ?>
</tr>
</table>
<?php if ($num4 == 0){?>
 <ul>
    <li> No reviews yet.</li>
 </ul>
<?php }?>

<hr />


<h4 align="center"> Cuision images using this recipe</h4>
<table>
<?php if ($numi2 == 0){?>
 <ul>
    <li> No images are added yet.</li>
 </ul>
<?php } else{
	$rowCount = 0;
    $max_recipei = $numi2;
	$max_column = 2;
  if ($max_recipei % $max_column == 0) {
  $max_per_column=$max_recipei/$max_column;
  } else {
  $max_per_column=$max_recipei/$max_column +1;
  }
    foreach($ai2 as $key => $value) if ($rowCount < $max_recipei) { $MyPhoto=  $value['ReviewImgDir'];?>
   	<?php if($rowCount % $max_per_column == 0) {  ?>
    <td><table>
    <?php } ?>                   
    <ul>
         <li><?php echo "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'height = 200/>";?></li>
  </ul>
    <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
    </table></td> 
   	<?php } ?>                  
	<?php $rowCount++; }}?>
 <br><br>
</table>
<hr />

<h5><?php echo '<a href="comment.php?rid='. $rid.'">';?>Add a review or comment</a> </h5>

<hr />

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
   		<?php }    	//	get image path  
		$rid = $value["rid"];
		$rtitle = $value["rtitle"];
		$rServNum= $value["rServNum"];
		$rCookTime= $value["rCookTime"];
		$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 1");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			?>
       	<tr>
         <td><?php echo '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." with serving number: ".$rServNum."<br>" .$iId . "<br> Cook Time: ".$rCookTime."minutes <br> <br>"; ?></td>
       	</tr>
       	<?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     		</table></td> 
   		<?php } ?>                  
		<?php $rowCount++; } 
	}?>
</tr>
</table>

</body>
</html>
