<?php
require('authenticate.php');

if (isset($_SESSION['logged_in'])) 
{ 
  header("Location: home.php?category=Home&&subcat=Monthly Fee&&welcome=".$_SESSION['welcome_message']." "); 
  exit;
}

?>
