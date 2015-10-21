<?php

if(isset($_POST['brand1']) && isset($_POST['brand2'])){
	$brand1=$_POST['brand1'];
	$brand2=$_POST['brand2'];
	$output = shell_exec("E:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe brand.R $brand1 $brand2 2>&1");//supply path to your Rscript.exe file
	echo "Result contains ";
    echo "<pre>$output</pre>";
}
/*
if(isset($_GET['N']))
{
  $N = $_GET['N'];
  echo "<h1>$N</h1>";
  $output = shell_exec("E:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe script.R $N 2>&1");//supply path to your Rscript.exe file
  echo "Result contains ";
  echo "<pre>$output</pre>";
  echo "</br>";
}else echo "<h1>Pass me some integer value baby! example: index.php?N=10</h1>"*/

?>

<html>
<body>
<form name="brand" method="post" action="">
<b>Enter the 2 brands names to compare and generate analysis report</b></br>
<input type="text" name="brand1"/>
<input type="text" name="brand2"/>
<input type="submit" value="generate"/>
<input type="reset" value="clear">
</form>
</body>
</html>