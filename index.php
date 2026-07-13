<?php
session_start();
error_reporting(0);
include('includes/config.php');

if($_SESSION['login']!=''){
    $_SESSION['login']='';
}

if(isset($_POST['login'])) 
{
$email=$_POST['emailid'];
$password=md5($_POST['password']); 

$sql ="SELECT EmailId,Password,StudentId,Status FROM tblstudents 
       WHERE EmailId=:email and Password=:password";

$query= $dbh->prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->bindParam(':password', $password, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
    foreach ($results as $result) {
        $_SESSION['stdid']=$result->StudentId;

        if($result->Status==1){
            $_SESSION['login']=$_POST['emailid'];
            echo "<script>document.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Your Account Has been blocked. Contact admin');</script>";
        }
    }
} 
else{
    echo "<script>alert('Invalid Details');</script>";
}
}
?>

<!DOCTYPE html>


<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Online Library Management System</title>

<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>

<?php include('includes/header.php');?>

<div class="content-wrapper">
<div class="container">

<!-- SLIDER -->
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div id="carousel-example" class="carousel slide" data-ride="carousel">

<div class="carousel-inner">
<div class="item active">
<img src="assets/img/1.jpg" />
</div>
<div class="item">
<img src="assets/img/2.jpg" />
</div>
<div class="item">
<img src="assets/img/3.jpg" />
</div>
</div>

<ol class="carousel-indicators">
<li data-target="#carousel-example" data-slide-to="0" class="active"></li>
<li data-target="#carousel-example" data-slide-to="1"></li>
<li data-target="#carousel-example" data-slide-to="2"></li>
</ol>

<a class="left carousel-control" href="#carousel-example" data-slide="prev">
<span class="glyphicon glyphicon-chevron-left"></span>
</a>

<a class="right carousel-control" href="#carousel-example" data-slide="next">
<span class="glyphicon glyphicon-chevron-right"></span>
</a>

</div>
</div>
</div>

<hr />

<!-- LOGIN -->
<div class="row pad-botm">
<div class="col-md-12">
<h4 class="header-line">USER LOGIN FORM</h4>
</div>
</div>

<div class="row">
<div class="col-md-6 col-md-offset-3">

<div class="panel panel-info">
<div class="panel-heading">LOGIN FORM</div>

<div class="panel-body">
<form method="post">

<div class="form-group">
<label>Email</label>
<input class="form-control" type="text" name="emailid" required />
</div>

<div class="form-group">
<label>Password</label>
<input class="form-control" type="password" name="password" required />
<p><a href="user-forgot-password.php">Forgot Password?</a></p>
</div>

<!-- NORMAL LOGIN -->
<button type="submit" name="login" class="btn btn-info">LOGIN</button>



<br><br>

<a href="signup.php">Not Register Yet</a>

</form>
</div>
</div>

</div>
</div>

<!-- 🤖 CHATBOT -->
<div class="row">
<div class="col-md-6 col-md-offset-3">

<div class="panel panel-success">
<div class="panel-heading">🤖 Library Assistant</div>

<div class="panel-body">

<input type="text" id="chatInput" class="form-control" placeholder="Ask something..." />
<br>
<button onclick="sendMsg()" class="btn btn-success">Send</button>

<br><br>
<p id="response" style="font-weight:bold;"></p>

</div>
</div>

</div>
</div>

</div>
</div>

<?php include('includes/footer.php');?>

<!-- JS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>

<!-- 🤖 CHATBOT SCRIPT -->
<script>
function sendMsg(){
    let msg = document.getElementById("chatInput").value;

    fetch("chatbot.php?msg=" + msg)
    .then(res => res.text())
    .then(data => {
        document.getElementById("response").innerHTML = data;
    });
}
</script>



</body>
</html>