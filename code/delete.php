<?php  
session_start();
if(!isset($_SESSION['rid']) or !isset($_SESSION['uname'])){
   header("Location: index.php");
}
 include_once 'dbconnect.php'; 
  $sql = "DELETE FROM Ingredient WHERE iid = '".$_POST["id"]."'";  
 if(mysqli_query($con, $sql))  
 {  
      echo 'Data Deleted';  
 }  
 ?>  