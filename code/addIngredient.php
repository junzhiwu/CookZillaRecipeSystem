
	 

 

<html>  
      <head>  
           <title>Live Table Data Edit</title>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
      </head>  
      <body>  
      
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
                <li><a href="AddRecipe.php">Add another Recipe</a></li>
                <li><a href="UserInfo.php">Back to your webpage</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
           <div class="container">  
                <br />  
                <br />  
                <br />  
                <div class="table-responsive">  
                     <h3 align="center">Edit Ingredient</h3><br />  
                     <div id="live_data"></div>                 
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      function fetch_data()  
      {  
           $.ajax({  
                url:"select.php",  
                method:"POST",  
                success:function(data){  
                     $('#live_data').html(data);  
                }  
           });  
      }  
      fetch_data();  
      $(document).on('click', '#btn_add', function(){  
           var Iname = $('#Iname').text();  
           var Iqty = $('#Iqty').text();  
           var Unit = $('#Unit').text(); 
           if(Iname == '')  
           {  
                alert("Enter Ingredient name");  
                return false;  
           }  
           if(Iqty == '')  
           {  
                alert("Enter Ingredient quantity");  
                return false;  
           }  
           if(Unit == '')  
           {  
                alert("Enter Ingredient unit");  
                return false;  
           }  
           $.ajax({  
                url:"insert.php", 
                method:"POST",  
                data:{Iname:Iname, Iqty:Iqty, Unit: Unit},  
                dataType:"text",  
                success:function(data)  
                {  
                     alert(data);  
                     fetch_data();  
                }  
           })  
      });  
     function edit_data(id, text, column_name)  
      {  
           $.ajax({  
                url:"edit.php",  
                method:"POST",  
                data:{id:id, text:text, column_name:column_name},  
                dataType:"text",  
                success:function(data){  
                     alert(data);  
                }  
           });  
      }  
      $(document).on('blur', '.Iname', function(){  
           var id = $(this).data("id1");  
           var Iname = $(this).text();  
           edit_data(id, Iname, "Iname");  
      });  
      $(document).on('blur', '.Iqty', function(){  
           var id = $(this).data("id2");  
           var Iqty = $(this).text();  
           edit_data(id,Iqty, "Iqty");  
      }); 
       $(document).on('blur', '.Unit', function(){  
           var id = $(this).data("id3");  
           var Unit = $(this).text();  
           edit_data(id,Unit, "Unit");  
      }); 
      $(document).on('click', '.btn_delete', function(){  
           var id=$(this).data("id4");  
           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"delete.php",  
                     method:"POST",  
                     data:{id:id},  
                     dataType:"text",  
                     success:function(data){  
                          alert(data);  
                          fetch_data();  
                     }  
                });  
           }  
      });  
 });  
 </script>  