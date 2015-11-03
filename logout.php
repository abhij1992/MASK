<?php
  session_start();   // start current session
  session_destroy(); //destroy all session data
  header('Location: index.php');
?>