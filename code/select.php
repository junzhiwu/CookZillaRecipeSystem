<?php  
 session_start();
 if(!isset($_SESSION['rid']) or !isset($_SESSION['uname'])){
   header("Location: index.php");
 }

 include_once 'dbconnect.php'; 
 $rid = $_SESSION['rid']-1;
 $output = '';  
 
 $sql = "SELECT * FROM Ingredient where rid =".$rid;  
 $result = mysqli_query($con, $sql);  
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="10%">Id</th>  
                     <th width="20%"> Name</th>  
                     <th width="20%"> Quantity</th>
                     <th width="20%"> Unit</th>  
                     <th width="10%">Delete</th>  
                </tr>';  
 if($result or mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td>'.$row["iid"].'</td>  
                     <td class="Iname" data-id1="'.$row["iid"].'" contenteditable>'.$row["Iname"].'</td>  
                     <td class="Iqty" data-id2="'.$row["iid"].'" contenteditable>'.$row["Iqty"].'</td>  
                     <td class="Unit" data-id3="'.$row["iid"].'" contenteditable>'.$row["Unit"].'</td>  
                     <td><button type="button" name="delete_btn" data-id4="'.$row["iid"].'" class="btn btn-xs btn-danger btn_delete">x</button></td>  
                </tr>  
           ';  
      }  
      $output .= '  
           <tr>  
                <td></td>  
                <td id="Iname" contenteditable></td>  
                <td id="Iqty" contenteditable></td> 
                <td id="Unit" contenteditable></td>  
                <td><button type="button" name="btn_add" id="btn_add" class="btn btn-xs btn-success">+</button></td>  
           </tr>  
      ';  
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Data not Found</td>  
                     </tr>';  
 }  
 $output .= '</table>  
      </div>';  
 echo $output;  
 ?>  