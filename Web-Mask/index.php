<?php

if(isset($_GET['N']))
{
  $N = $_GET['N'];
  echo "<h1>$N</h1>";
  $output = shell_exec("D:\Windows_Program_Files\R-3.2.2\bin\\rscript.exe script.R $N 2>&1");//supply path to your Rscript.exe file
  echo "Result contains ";
  echo "<pre>$output</pre>";
  echo "</br>";
}else echo "<h1>Pass me some integer value baby! example: index.php?N=10</h1>"
?>