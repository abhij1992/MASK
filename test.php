<?php 
function isEnabled($func) {
    return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
}
if (isEnabled('shell_exec')) {
    shell_exec('echo "hello world"');
	echo "here";
	$output =shell_exec("dir");
	echo $output;
	$keyword="hello";
	//$output = shell_exec("C:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe sentiment.R $keyword");//supply path to your Rscript.exe file
	$output = shell_exec("C:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe sentiment.R");//supply path to your Rscript.exe file
	echo "<br><br><br>".$output;
}
?>