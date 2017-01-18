
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
	$tname = $_GET['tname'];
	$query="SELECT tid FROM Tag  WHERE tname ='".$tname."'limit 1" ;
	$result = mysqli_query($con,$query);
	if ($result) $num = mysqli_num_rows($result); 
	else { echo "false query";}
	if($num != 0) {
		$row = mysqli_fetch_assoc($result);
		if(isset($_SESSION['uname'])) {
			$tid = $row["tid"];
			$uname = $_SESSION['uname'];
	    	mysqli_query($con, "INSERT INTO readTag(tid,rname,logTime) VALUES('".$tid."', '".$uname."',NOW())");
	    }
	}
	$query="SELECT * FROM Recipe natural join RecipeTag natural join Tag WHERE tname ='".$tname."'" ;
// 	echo $query;
	$result = mysqli_query($con,$query);
	if ($result) $num = mysqli_num_rows($result); 
	else { echo "false query";}
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


<table width="100%">
<tr>
<?php
	if($num == 0) echo '<div class="alert alert-danger"><strong> "No recipe is found. Please try again in <a href="index.php"> home page.</a>"</strong>'.'</div> ';
	else { 

		while($row = mysqli_fetch_assoc($result)){
    		$narray[] = $row; // Inside while loop
		}
    	$rowCount = 0;
    	$max_recipe = $num;
  		$max_column = 2;
  		if ($max_recipe % $max_column == 0) {
  			$max_per_column=$max_recipe/$max_column;
  		} else {
  			$max_per_column=$max_recipe/$max_column +1;
  		}
    	foreach($narray as $key => $value) if ($rowCount < $max_recipe) { ?>
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