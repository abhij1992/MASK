<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
$pluname="";
include 'connection.php';

function myFilter($var){
  return ($var !== NULL && $var !== FALSE && $var !== '');
}

if(isset($_POST['brand1']) && isset($_POST['brand2']))
{
	$brand1=$_POST['brand1'];
	$brand2=$_POST['brand2'];
	$output = shell_exec("E:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe brand.R $brand1 $brand2");//supply path to your Rscript.exe file
	//$output = shell_exec("C:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe brand.R $brand1 $brand2");//supply path to your Rscript.exe file
	//echo "Result contains ";
    //echo "<pre>$output</pre>";	
	$table=get_string_between($output,"brand1-start","brand1-end");
	//echo "<pre>$table</pre>";
	$brand1values = explode("\n",$table);
	//echo "Brand 1 X axis = ".$brand1values[1]." \n Y axis = ".$brand1values[2]."";
	$table=get_string_between($output,"brand2-start","brand2-end");
	//echo "<pre>$table</pre>";
	$brand2values = explode("\n",$table);
	//echo "Brand 2 X axis = ".$brand2values[1]." \n Y axis = ".$brand2values[2]."";	
}
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Home</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>
	<script src="js/chartjs/chart.min.js"></script> <!--Include chart.js file-->

   <script>
	//chart related code block
	window.onload = draw; 
	var aData={
	<?php
	
	$x = explode(" ",$brand1values[1]);
	$x=array_filter($x,'myFilter');
	echo "labels:[";
	$i=0;
	foreach($x as $k=>$v){
		//if(!empty($v) || $v==0) {
		echo $v;
		$i++;
		if($i<count($x)) echo ",";
	}
	echo "],"; ?>
		
	datasets: [
        {
            label: "Dataset",
            fillColor: "#<?php echo substr(md5(rand()), 0, 6);?>",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [
			<?php
				$x = explode(" ",$brand1values[2]);
				$x=array_filter($x,'myFilter');
				$i=0;
				foreach($x as $k=>$v){
				if(!empty($v)) {
				echo $v;
				$i++;
				if($i<count($x)) echo ",";
				}
			}
			?>
				]
        }
			  ]	
	};
	
	var bData = {
	<?php
	
	$x = explode(" ",$brand2values[1]);
	$x=array_filter($x,'myFilter');
	echo "labels:[";
	$i=0;
	foreach($x as $k=>$v){
		//if(!empty($v) || $v==0) {
		echo $v;
		$i++;
		if($i<count($x)) echo ",";
		
	}
	echo "],"; ?>
    datasets: [
        {
            label: "Dataset",
            fillColor: "#<?php echo substr(md5(rand()), 0, 6);?>",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [
			<?php
				$x = explode(" ",$brand2values[2]);
				$x=array_filter($x,'myFilter');
				$i=0;
				foreach($x as $k=>$v){
				if(!empty($v)) {
				echo $v;
				$i++;
				if($i<count($x)) echo ",";
				}
			}
			?>
			]
        }
			  ]
	};
	function draw(){
	//alert("drawing graph!");
	var actx=document.getElementById("brandA").getContext("2d");
	var myaBarChart= new Chart(actx).Bar(aData);
	var bctx = document.getElementById("brandB").getContext("2d");
	var mybBarChart = new Chart(bctx).Bar(bData);
	}

	</script>
</head>


<body class="nav-md">

    <div class="container body">


        <div class="main_container">

            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">

                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-line-chart"></i> <span>Project MASK</span></a>
                    </div>
                    <div class="clearfix"></div>


                    <!-- menu prile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src="images/user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?php echo $_SESSION["unames"]; ?></h2>
                        </div>
                    </div>
                    <!-- /menu prile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
						
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a href="chartjs.php"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                                    
                                </li>
							
                            </ul>
							
                        </div>
                        <div class="menu_section">
							
							
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
					<!--
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
					-->
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">

                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION["unames"];?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li><a href="javascript:;">  Profile</a>
                                    </li>
                                    
                                    <li>
                                        <a href="javascript:;">Help</a>
                                    </li>
                                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>

                            

                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>
                    Enter Brand names to compare
                    <small>
                        Do not include '#'
                    </small>
                </h3>
                        </div>

                       
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">


                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Search</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li class="dropdown">
                                            
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
											<form name="brandcomparision" action="" method="post">
                                            <div class="col-md-12 col-sm-6 col-xs-12">
												<b>Brand A:</b>
                                                <input type="text" id="brand1" name="brand1" required="required" class="form-control col-md-7 col-xs-12"></br>
												<b>Brand A:</b>
												<input type="text" id="brand2" name="brand2" required="required" class="form-control col-md-7 col-xs-12"></br>
                                            </div>
											<div class="ln_solid"></div>
											<div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <button type="submit" class="btn btn-primary">Cancel</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
												
                                            </div>
                                        </div>
										</form>
										
										<div>
										<?php
										if(isset($_POST['brand1'])){
											echo "<h3>".$_POST['brand1']."</h3>";
										?>
										<canvas id="brandA" align="center" width="400" height="400"></canvas>
										<?php 
										}
										?>
										</div>
										<div style="margin-left:500px;margin-top:-450px">
										<?php
										if(isset($_POST['brand2'])){
											echo "<h3>".$_POST['brand2']."</h3>";
										?>
										<canvas id="brandB" align="center" width="400" height="400"></canvas>
										<?php 
										}
										?>
										</div>
                                </div>
								
									
                            </div>
                        </div>

                    </div>
                </div>

                <!-- footer content -->
                <footer>
                    <div class="">
                        <p class="pull-right">Market Analysis based on Social NetWorK
                            <span class="lead"> <i class="fa fa-paw"></i> |MASK</span>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->

            </div>
            <!-- /page content -->
        </div>

    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <script src="js/bootstrap.min.js"></script>

    <!-- chart js -->
    <script src="js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="js/icheck/icheck.min.js"></script>

    <script src="js/custom.js"></script>

    <script>
        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100)
        };

    </script>
	
</body>

</html>