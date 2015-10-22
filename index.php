<!DOCTYPE html>
<html lang="en">
<?php
session_start();
$pluname="";
include 'connection.php'; 

 //the below code block is required as it controls which user can access the pages,please don't remove it
if(isset($_POST['submit1'])){
		//conn object is initialized in connection.php
	if ($conn->connect_error) { //Check connection
		die("Connection failed: " . $conn->connect_error);
	}
	$pluname=$_POST['uname'];
	$plpassword="";
	$sql = "SELECT id,username,password FROM user_info where username='".$_POST['uname']."' ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc(); 
		//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		if($_POST['pword'] == $row["password"])
		{
		    $_SESSION["unames"] = $_POST['uname'];
			$_SESSION["name"]=$row["name"];
			$_SESSION["user_id"]=$row["id"];
			header('Location:chartjs.php');
		}
		else{
			$pluname="<div><h3></i> Invalid Password</h3></div>";
		}
	} else {
			$pluname="<div><h3></i> Invalid Username</h3></div>";
	}
$conn->close();
}


$sorry="";

if(isset($_POST['submit2'])){
	if ($conn->connect_error) { //Check connection
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT username,password FROM user_info where username='".$_POST['uname']."' ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$sorry="<div><h4>The user name already exists</h4></div>";
	}
	else{
		$sql="INSERT INTO `mask`.`user_info` (`id`, `username`, `password`, `name`) VALUES (NULL, '".$_POST['user_name']."', '".$_POST['password']."', '".$_POST['uname']."');";
		$conn->query($sql);
		$sql = "SELECT id,username,password FROM user_info where username='".$_POST['user_name']."' ";
		$result = $conn->query($sql);
		$_SESSION["unames"] = $_POST['uname'];
		$_SESSION["name"]=$row["name"];
		$_SESSION["user_id"]=$row["id"];
		header('Location:chartjs.php');
	}
	$conn->close();
}

?>
	
	
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Mask </title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body style="background:#F7F7F7;">
    
    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>

        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">
                    <form method="POST">
                        <h1>Login Form</h1>
                        <div>
                            <input type="text" class="form-control" placeholder="Username" name="uname" required/>
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" name="pword" required />
                        </div>
                        <div>
							<button type="submit" name="submit1" class="btn btn-success" value="submit1">Submit</button>
                            <a class="reset_pass" href="#">Lost your password?</a>
                        </div>
                        <?php echo $pluname;?>
						<div class="clearfix"></div>
						
                        <div class="separator">

                            <p class="change_link">New to site?
                                <a href="#toregister" class="to_register"> Create Account </a>
                            </p>
                            <div class="clearfix"></div>
                            <br />
                            <div>
                                <h1></i> Project MASK</h1>

                                <h2>Market Analysis based on Social Network</h2>
                            </div>
                        </div>
                    </form>
                    <!-- form -->
                </section>
                <!-- content -->
            </div>
            <div id="register" class="animate form">
                <section class="login_content">
                    <form method="POST">
                        <h1>Create Account</h1>
                        <div>
                            <input type="text" class="form-control" placeholder="Name"  name="user_name" required="" />
                        </div>
                        <div>
                            <input type="text" class="form-control" placeholder="User name" name="uname" required="" />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" required="" name="password" />
                        </div>
                        <div>
                            <button type="submit" name="submit2"  class="btn btn-success" value="submit2">Submit</button>
                        </div>
						<div>
							<?php echo $sorry; ?>
						</div>
                        <div class="clearfix"></div>
                        <div class="separator">

                            <p class="change_link">Already a member ?
                                <a href="#tologin" class="to_register"> Log in </a>
                            </p>
                            <div class="clearfix"></div>
                            <br />
                            <div>
                                <h1></i> Project MASK</h1>

                                <h2>Market Analysis based on Social Network</h2>
                            </div>
                        </div>
                    </form>
                    <!-- form -->
                </section>
                <!-- content -->
            </div>
        </div>
    </div>

</body>

</html>