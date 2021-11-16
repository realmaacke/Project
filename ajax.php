<?php
//Including Database configuration file.
include("autoload.php");
//Getting value of "search" variable from "script.js".
if (isset($_POST['search'])) {
//Search box value assigning to $Name variable.
   $Name = $_POST['search'];
//Search query.
   $Query = DB::query("SELECT username FROM users WHERE username LIKE '%$Name%' LIMIT 5");
   echo '
<ul>
   ';
   //Fetching result from database.
  foreach ($Query as $q) {
       ?>
   <!-- Creating unordered list items.
        Calling javascript function named as "fill" found in "script.js" file.
        By passing fetched result as parameter. -->
   <li onclick="fill("<?php echo $q['username'] ?> ")">
   <a>
   <!-- Assigning searched result in "Search box" in "search.php" file. -->
       <?php echo $q['username']; ?>
   </li></a>
   <!-- Below php code is just for closing parenthesis. Don't be confused. -->
   <?php
}}
?>
</ul>