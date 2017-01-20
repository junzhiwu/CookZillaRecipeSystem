# CookZilla Recipe System (PHP, MySQL, HTML, JavaScript)
A system which provides a platform for people to share recipes and improve cooking skills. 

Specifically, the system provides following functions:

1) Register and log in (implemented in index.php, register.php, login.php and logout.php)

2) Registed user can edit profile. User's personal page displays the recipes that user favorites and system recommended,  visitory history and info of joined groups and RSVP meetings (implemented in UserInfo.php and EditProfile.php). 

3) Search recipes. Visitors can search for recipes using keywords, tags and the average rate. After browsing the recipes, they can bookmark the recipes if they’re satisfied with the result dish or they just want to try it. The visiting history of recipes and tags will be logged (implemented in index.php, Search.php, goodRecipes.php, recipesByTag.php and recipe.php). 

4) Registered user can post recipes, review and rate recipes, and provide suggestions to improve the dish (implemented in recipe.php, UserInfo.php, AddRecipe.php and comment.php).

5) When creating recipes, user can add ingredients, steps and tags, and upload images (implemented in AddRecipe.php, addIngredient.php, select.php, insert.php, edit.php and delete.php).

6) Registered user can organize or join the group. For specific topics (e.g. for festivals or favorite food), the members in the group can organize or RSVP the cooking meetings in a group (implemented in group.php, addGroup.php and meeting.php).

To run the code, 
1) copy the code folder to "/Library/WebServer/Documents/proj2" (MAC), and create the database according to "Realational Schema.txt". 
2) Start MySQL server in system preference, fill your username and password in dbconnect.php. 
3) Then open a browser, type "http://localhost/code/index.php" and run it.
