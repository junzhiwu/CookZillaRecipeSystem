<?php  
 session_start();
 if(!isset($_SESSION['rid']) or !isset($_SESSION['uname'])){
   header("Location: index.php");
}
 include_once 'dbconnect.php'; 
 $rid = $_SESSION['rid']-1; 
 $sql = "INSERT INTO Ingredient(rid,Iname, Iqty, Unit) VALUES(".$rid.",'".$_POST["Iname"]."', '".$_POST["Iqty"]."', '".$_POST["Unit"]."')";  
 if(mysqli_query($con, $sql))  
 {  
      echo 'Data Inserted';  
 }  
 ?>  