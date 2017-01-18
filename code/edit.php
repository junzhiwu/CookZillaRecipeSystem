<?php  
 session_start();

if(!isset($_SESSION['rid']) or !isset($_SESSION['uname'])){
   header("Location: index.php");
}
	 
 include_once 'dbconnect.php';  
 $id = $_POST["id"];  
 $text = $_POST["text"];  
 $column_name = $_POST["column_name"];  
 $sql = "UPDATE Ingredient SET ".$column_name."='".$text."' WHERE iid='".$id."'";  
 if(mysqli_query($con, $sql))  
 {  
      echo 'Data Updated';  
 }  
 ?>  