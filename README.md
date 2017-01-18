# CookZillaRecipeSystem
a project for database

The system has the following features
1) Register and log in (implemented in register.php, login.php and logout.php)

2) Registed user can edit profile. User's personal page displays the recipes that user favorites and system recommended,  visitory history and info of joined groups and RSVP meetings. (implemented in UserInfo.php and EditProfile.php)

3) Search recipes. Visitors can search for recipes using keywords, tags and the average rate. After browsing the recipes, they can bookmark the recipes if they’re satisfied with the result dish or they just want to try it. The visiting history of recipes and tags will be logged. 

4) Every one can read the recipes. Registered user can post recipes, review and rate recipes, and provide suggestions to improve the dish.

5) When creating recipes, user can add ingredients, steps and tags, and upload images.

6) Registered user can organize or join the group. For specific topics (e.g. for festivals or favorite food), the members in the group can organize or RSVP the cooking meetings in a group.

To run the code, copy the code folder to "/Library/WebServer/Documents/proj2" (MAC)
Start MySQL server in system preference, fill your username and password in dbconnect.php. 
Then open a browser, type "http://localhost/proj2/index.php" and run it.
