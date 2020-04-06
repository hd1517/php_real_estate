<?php
include "include/config.php";

// check if agent is signed in
if (isset($_SESSION['agent_id'])) {
  session_destroy();
  header("Location: signin.php");
}

// check if user is logged in
if (isset($_SESSION['user_id'])) {
  session_destroy();
  header("Location: index.php");
}


?>
