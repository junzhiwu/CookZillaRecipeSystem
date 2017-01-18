<?php
session_start();
include_once 'dbconnect.php';

if (isset($_POST["submit"])){	
	$error = false;
	if(isset($_POST['keyword']) and $_POST['keyword'] != ''){
		$_SESSION['rkeyword']=$_POST['keyword'];
  		header("Location: Search.php");
  	}
}

if (isset($_POST["submitg"])){	
	$error = false;
	if(isset($_POST['gkeyword']) and $_POST['gkeyword'] != ''){
		
  	}
}




//find group
if (isset($_POST['submitg'])) {
	if($_POST['gkeyword'] and $_POST['gkeyword'] != ''){
		$gkeyword = mysqli_real_escape_string($con,$_POST['gkeyword']);
		$queryG='SELECT  distinct *  FROM CookingGroup WHERE gname LIKE "%'.$gkeyword.'%" OR gDesc LIKE "%'.$gkeyword.'%"' ;
// 		echo $queryG;
		$resultG = mysqli_query($con,$queryG);
		if ($resultG) $numG = mysqli_num_rows($resultG); 
		else { echo "false query";}
	}
}
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
<!-- 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
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


<!-- recipe searching -->

<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="box">
					<img src="http://localhost/proj2/upload/logo.png  "/>
				</div>
				<form method = 'POST'>
				<div class="form-group">
					<input type= "text" name="keyword" class="form-control" placeholder = "What would you like to cook?"/>
				</div>
				<input type="submit" name="submit" class="btn btn-success btn-lg" value="search" />
				</form>
			</div>
	 	</div>
</div>

<h3 align="center">Popular recipes in this week</h3>
<?php
//  $query1="SELECT * FROM Recipe WHERE mname LIKE '%".$_POST['keyword']."%' AND rtime >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)" ;
 	$query1="SELECT * FROM Recipe WHERE rtime >= DATE_ADD(CURDATE(), INTERVAL -7 DAY) and rid in (select rid from review natural join recipe group by rid having avg(rate) > 4)" ;
// 	$query1=" SELECT * FROM Recipe WHERE rtime >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)" ; //need rate
	$result = mysqli_query($con,$query1);
	$results = mysqli_num_rows($result); 
	$s='<p align="center">';
	if ($results>0) {
		$colNum = 3; $i = 0;
   		while($row = $result->fetch_assoc()) {
   			$i = $i+1;
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
    echo $s.'</div>';?>


       
<h3 align="center"> Latest recipes</a></h3> 


<?php
// 	$query1="SELECT * FROM Recipe WHERE mname LIKE '%".$_POST['keyword']."%' AND rtime >= dateadd(day,-7,getdate())" ;
	$query2=" SELECT * FROM Recipe ORDER BY rtime DESC LIMIT 5" ;
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


    
<h3 align="center"> Or try our popular searches  </h3> 
<?php
	$query3 ="SELECT tname FROM Tag";
	$result = mysqli_query($con,$query3);
	$results = mysqli_num_rows($result); 
	$s="<p align='center'><b>Select Recipes by Tags: </b></a>";
   	if ($results>0) {
   		while($row = $result->fetch_assoc()) {
   			$tname = $row["tname"];
			// $query4 ="SELECT * FROM Tag natural join Recipe natural join RecipeTag where tname = $tname";
			$s.= '<a href="recipesByTag.php?tname='. $tname . '">' . $tname. '</a>'."   .  ";
		}
	}
    echo $s.'<br></div>';
    
    echo "<p align='center'><b><a href='goodRecipes.php'>Recipes rated more than 4 star: </b></a><p>";

?>


<hr /> 
 
<h3 align='center'> </h3>
<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form method = 'POST'>
				<div class="form-group">
					<input type= "text" name="gkeyword" class="form-control" placeholder = "Want to join a group? find one!"/>
				</div>
				<input type="submit" name="submitg" class="btn btn-success btn-lg" value="find" />
				</form>
			</div>
	 	</div>
</div>
<table width="100%"> 
		<tr>
	<?php
		if(!isset($numG)){
			echo "<h4  align='center'>Please enter the keyword</h4>";
		}else 
		if($numG == 0)  {
			echo "<h4  align='center'>No group is matched. Please try other keyword.</h4>";
		} else {
			while($rowG = mysqli_fetch_assoc($resultG)){
    			$aG[] = $rowG;
			}
    	$rowCount = 0;
    	$max_recipeG = $numG;
  		$max_column = 3;
  		if ($max_recipeG % $max_column == 0) {
  			$max_per_column=$max_recipeG/$max_column;
  		} else {
  			$max_per_column= ($max_recipeG/$max_column) +1;
  		}
    	foreach($aG as $key => $value) if ($rowCount < $max_recipeG) { ?>
   		<?php if($rowCount % $max_per_column == 0) {  ?>
     	<td><table>
   		<?php } ?>                   
       	<ul>
        	 <li><?php echo '<a href="group.php?gid=' . $value['gid'] . '">' .$value['gname']. '</a>'."<br>";  ?></li> 
        	 
       </ul>
       <?php if($rowCount % $max_per_column == $max_per_column -1) {  ?>
     </table></td> 
   	<?php } ?>                  
	<?php $rowCount++; }} ?>
	</tr>
	</table>

</body>
</html>