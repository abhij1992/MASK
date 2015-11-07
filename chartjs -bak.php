<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
if(!isset($_SESSION['user_id'])) //every page checks if logged in ,and if not then go to login page , we are already in login page so no else condition
{
     header('Location: index.php'); 
 
}
$pluname="";
include 'connection.php';

function myFilter($var){
  return ($var !== NULL && $var !== FALSE && $var !== '');
}

function insertTable($table,$tag){
	global $conn;
	if ($conn->connect_error) { //Check connection
		die("Connection failed: " . $conn->connect_error);
	}
	if(isset($_GET["is_fav"])){
		$is_fav=1;
	}
	else $is_fav=0;
	if($res=$conn->query("SELECT count(*) as entry from hashtags WHERE `tag`='".$tag."';")){
		$row=$res->fetch_assoc();
		if($row["entry"]=='0'){
			if(!$conn->query("INSERT INTO hashtags(tag,date_time,graph_values,user_id,is_fav) VALUES('".$tag."','".date("Y-m-d H:i:s")."','".$table."','".$_SESSION["user_id"]."','".$is_fav."');")){
				echo "Failed to insert";
			}
		}else{
			if(!$conn->query("UPDATE hashtags set date_time='".date("Y-m-d H:i:s")."',graph_values='".$table."' WHERE tag='".$tag."';")){
				echo "Failed to update";
			}
		}
	}
}
function getTable($tag){
	global $conn;
	$sql="SELECT graph_values FROM hashtags WHERE tag='".$tag."';";
	$res=$conn->query($sql);
	$row=$res->fetch_assoc();
	return $row["graph_values"];
}
if(isset($_GET['hashtag']))
{
	$keyword=$_GET['hashtag'];
	//$output = shell_exec("E:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe sentiment.R $keyword");//supply path to your Rscript.exe file
	$output = shell_exec("C:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe sentiment.R $keyword");//supply path to your Rscript.exe file
	//echo "Result contains ";
    //echo "<pre>$output</pre>";	
	$table=get_string_between($output,"table-start","table-end");
	if(isset($_GET['compare']) && $_GET['compare']=='1'){
		$previousTable=getTable($keyword);
		$previousValues=explode("\n",$previousTable);
	}
	insertTable($table,$keyword);
	//echo "<pre>$table</pre>";
	$values = explode("\n",$table);
	//echo "X axis = ".$values[1]." \n Y axis = ".$values[2]."";
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

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

	<script>
	//chart related code block
	window.onload = draw; // try to draw the chart after pages load if data give or else does nothing
	<?php
	if(isset($_GET["compare"]) && $_GET["compare"]==1){
	?>
	var prevData={
	<?php
	
	$x = explode(" ",$previousValues[1]);
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
				$x = explode(" ",$previousValues[2]);
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
	<?php
	}
	?>
	
	var data = {
	<?php
	
	$x = explode(" ",$values[1]);
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
				$x = explode(" ",$values[2]);
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
	var ctx = document.getElementById("myChart").getContext("2d");
	var myBarChart = new Chart(ctx).Bar(data);
	<?php
	if(isset($_GET["compare"]) && $_GET["compare"]==1){
	?>
	var prevctx=document.getElementById("myPreviousChart").getContext("2d");
	var myPrevBarChart= new Chart(prevctx).Bar(prevData);
	<?php
	}
	?>
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
						
                            <h3>Sentimental</h3>
                            <ul class="nav side-menu">
                                <li><a  href='chartjs.php' ><i class="fa fa-search"></i> Single search </a>
                                    
                                </li>
							<li><a ><i class="fa fa-history"></i> Previous Hashtags <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <?php
											$sql="SELECT tag from hashtags LIMIT 10;";
											$res=$conn->query($sql);
												while($row = $res->fetch_assoc())
												{
													echo "<li><a href='chartjs.php?hashtag=".$row["tag"]."&compare=1'>".$row["tag"]."</a></li>";
												}
		
										?>
                                    </ul>
                                </li>
							<li><a><i class="fa fa-star"></i> Favourite   <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <?php
											$sql="SELECT tag from hashtags where is_fav=1;";
											$res=$conn->query($sql);
												while($row = $res->fetch_assoc())
												{
													echo "<li><a href='chartjs.php?hashtag=".$row["tag"]."'>".$row["tag"]."</a></li>";
												}
		
										?>
                                    </ul>
                                </li>
                            </ul>
							
							
							<h3>Word Cloud</h3>
                            <ul class="nav side-menu">
                                <li><a href="wordcloud.php"><i class="fa fa-cloud"></i> Word Cloud </a>
                                </li>
                                <li><a><i class="fa fa-history"></i> Word Cloud searches <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <?php
											$sql="SELECT tag from word_cloud LIMIT 10;";
											$res=$conn->query($sql);
												while($row = $res->fetch_assoc())
												{
													echo "<li><a href='word_cloud_past.php?hashtag=".$row["tag"]."'>".$row["tag"]."</a></li>";
												}
		
										?>
                                    </ul>
                                </li>
								<li><a><i class="fa fa-star"></i> Favourite   <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <?php
											$sql="SELECT tag from word_cloud where is_fav=1;";
											$res=$conn->query($sql);
												while($row = $res->fetch_assoc())
												{
													echo "<li><a href='word_cloud_past.php?hashtag=".$row["tag"]."'>".$row["tag"]."</a></li>";
												}
		
										?>
                                    </ul>
                                </li>
                            </ul>
							<h3>Comparison</h3>
                            <ul class="nav side-menu">
                                <li><a href="brand.php"><i class="fa fa-legal"></i>Comparison </a>
                                </li>
                            </ul>
                        </div>
                        <div class="menu_section">
							
							
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                   
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
                                    <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
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
                    Enter a Hashtag
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
											<form name="sentiment" action="" method="get">
                                            <div class="col-md-12 col-sm-6 col-xs-12">
                                                <input type="text" id="tag" name="hashtag" required="required" class="form-control col-md-7 col-xs-12"></br>
												<input type ="checkbox" name="is_fav" value="1">Favorite tag</br>
                                            </div>
											<div class="ln_solid"></div>
											<div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
											
												
                                                <button type="submit" class="btn btn-primary">Cancel</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
												
                                            </div>
                                        </div>
										</form>
										
										
                                </div>
								
									
                            </div>
                        </div>
						<?php
						if((isset($_GET["compare"]) && $_GET["compare"]==1)||(isset($_GET["hashtag"])))
						{
						?>
						<div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Sentimental<small>Chart</small></h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                       
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br>
                                    
                                    
						 
						<?php
						}
						?>
						<?php
						if(isset($_GET["compare"]) && $_GET["compare"]==1){
						?>
										<div style="float:left;">
										<h3>Previous Sentimental Chart</h3>
										
										<canvas id="myPreviousChart" align="center" width="400" height="400"></canvas>
										</div>
						<?php
						}
						?> 
						<?php
						if((isset($_GET["compare"]) && $_GET["compare"]==1)||(isset($_GET["hashtag"])))
						{
							if(isset($_GET["hashtag"]))
							{
						 ?>				
								<div style="float:left;">							
						<?php
							}
							else{
								echo "<div style='float:right;'>";
							}
						?>
							<h3>Current Sentimental Chart</h3>
										<canvas id="myChart" align="center" width="400" height="400"></canvas>
										</div>
								</div>
										
							</div>
                        </div>
									
                                
						<?php
						}
						?>
										
										

                    </div>
                </div>

                <!-- footer content -->
                <footer>
                    <div class="">
                        <p class="pull-right">Market Analysis based on Social NetWorK
                            <span class="lead"> <i class="fa fa-bar-chart"></i> |MASK</span>
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