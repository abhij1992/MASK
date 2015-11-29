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
	if(isset($_POST["is_fav"])){
		$is_fav=1;
	}
	else $is_fav=0;
	if($res=$conn->query("SELECT count(*) as entry from word_cloud WHERE `tag`='".$tag."' and user_id= ".$_SESSION['user_id'].";")){
		$row=$res->fetch_assoc();
		if($row["entry"]=='0'){
			if(!$conn->query("INSERT INTO word_cloud(tag,date_time,source_location,user_id,is_fav) VALUES('".$tag."','".date("Y-m-d H:i:s")."','".$table."','".$_SESSION["user_id"]."','".$is_fav."');")){
				echo "Failed to insert";
			}
		}else{
			if(!$conn->query("UPDATE word_cloud set date_time='".date("Y-m-d H:i:s")."',source_location='".$table."',is_fav=".$is_fav." WHERE tag='".$tag."' and user_id= ".$_SESSION['user_id'].";")){
				echo "Failed to update";
			}
		}
	}
}
if(isset($_GET['hashtag']))
{
	
	$sql="SELECT * from word_cloud WHERE `tag`='".$_GET['hashtag']."' and user_id= ".$_SESSION['user_id'].";";
	$res=$conn->query($sql);
	while($row = $res->fetch_assoc())
	{
		$old=$row["source_location"];
	}
	$keyword=$_GET['hashtag'];
	$output = shell_exec("E:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe WordCloud.R $keyword");//supply path to your Rscript.exe file
	//$output = shell_exec("C:\PROGRA~1\R\R-3.2.2\bin\\rscript.exe WordCloud.R $keyword");//supply path to your Rscript.exe file
	//echo "Result contains ";
    // echo "<pre>$output</pre>";	
	$table=get_string_between($output,"table-start","table-end");
	$filename=get_string_between($output,"filename-start","filename-end");
	$filename = substr($filename, 5, -2);
	//echo "<pre>$table</pre>";
	//$values = explode("\n",$table);
	//echo "X axis = ".$values[1]." \n Y axis = ".$values[2]."";
	//echo $filename;
	insertTable($filename,$keyword);
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
											$sql="SELECT tag from hashtags where user_id= ".$_SESSION['user_id']." LIMIT 10;";
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
											$sql="SELECT tag from hashtags where is_fav=1 and user_id= ".$_SESSION['user_id'].";";
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
											$sql="SELECT tag from word_cloud where user_id= ".$_SESSION['user_id']." LIMIT 10;";
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
											$sql="SELECT tag from word_cloud where is_fav=1 and user_id= ".$_SESSION['user_id'].";";
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
											<form name="sentiment" action="wordcloud.php" method="post" >
                                            <div class="col-md-12 col-sm-6 col-xs-12">
                                                <input type="text" id="tag" name="hashtag" required="required" class="form-control col-md-7 col-xs-12">
												<input type ="checkbox" name="is_fav" value="1">Favorite tag</br>
                                            </div>
											<div class="ln_solid"></div>
											
                                            <div class="form-group">
											 <div class="ln_solid"></div>
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
											

                                                <button type="submit" class="btn btn-primary">Cancel</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
												
                                            </div>
											
												
											
                                        </div>
										</form>
										
                                </div>

								
									
                            </div>
                        </div>
						
						
						
						
				<?php if(isset($_GET['hashtag']))
						{
				?>
						<div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Word Cloud <small>Associated words</small></h2>
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
									<img src="<?php echo $old; ?>" alt="Mountain View" style="width:450px;" align="center">
                                    <img src="<?php echo $filename; ?>" alt="Mountain View" style="width:450px;" align="center">
									<div class="bs-example" data-example-id="simple-jumbotron">
                                    <div class="jumbotron">
                                        <h3>Word counts</h3>
                                        <?php
											echo "<pre>$table</pre>";
										?>
                                    </div>
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