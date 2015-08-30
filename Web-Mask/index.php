<?php

if(isset($_POST['keyword'])){
	$keyword=$_POST['keyword'];
	$output = shell_exec("D:\Windows_Program_Files\R-3.2.2\bin\\rscript.exe sentiment.R $keyword 2>&1");//supply path to your Rscript.exe file
	echo "Result contains ";
    echo "<pre>$output</pre>";
}
/*
if(isset($_GET['N']))
{
  $N = $_GET['N'];
  echo "<h1>$N</h1>";
  $output = shell_exec("D:\Windows_Program_Files\R-3.2.2\bin\\rscript.exe script.R $N 2>&1");//supply path to your Rscript.exe file
  echo "Result contains ";
  echo "<pre>$output</pre>";
  echo "</br>";
}else echo "<h1>Pass me some integer value baby! example: index.php?N=10</h1>"*/

?>

<html>
<body>
<form name="analysis" method="post" action="">
<b>Enter the Keyword to generate analysis report</b></br>
<input type="text" name="keyword"/>
<input type="submit" value="generate"/>
<input type="reset" value="clear">
</form>
</body>
</html>