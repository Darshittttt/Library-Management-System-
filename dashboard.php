<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0){ 
    header('location:index.php');
}else{

$sid = $_SESSION['stdid'];

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>User Dashboard</title>

    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>

<?php include('includes/header.php');?>

<div class="content-wrapper">
<div class="container">

<!-- ================= DASHBOARD ================= -->
<div class="row pad-botm">
    <div class="col-md-12">
        <h4 class="header-line">User DASHBOARD 📚</h4>
    </div>
</div>

<div class="row">

<!-- TOTAL BOOKS -->
<a href="listed-books.php">
<div class="col-md-4">
<div class="alert alert-success text-center">
<i class="fa fa-book fa-5x"></i>

<?php 
$sql ="SELECT id FROM tblbooks";
$query = $dbh->prepare($sql);
$query->execute();
$listdbooks=$query->rowCount();
?>

<h3><?php echo $listdbooks;?></h3>
Books Listed
</div>
</div>
</a>

<!-- NOT RETURNED -->
<div class="col-md-4">
<div class="alert alert-warning text-center">
<i class="fa fa-recycle fa-5x"></i>

<?php 
$rsts=0;
$sql2 ="SELECT id FROM tblissuedbookdetails 
        WHERE StudentID=:sid 
        AND (RetrunStatus=:rsts OR RetrunStatus IS NULL OR RetrunStatus='')";

$query2 = $dbh->prepare($sql2);
$query2->bindParam(':sid',$sid,PDO::PARAM_STR);
$query2->bindParam(':rsts',$rsts,PDO::PARAM_STR);
$query2->execute();
$returnedbooks=$query2->rowCount();
?>

<h3><?php echo $returnedbooks;?></h3>
Books Not Returned
</div>
</div>

<!-- TOTAL ISSUED -->
<a href="issued-books.php">
<div class="col-md-4">
<div class="alert alert-info text-center">
<i class="fa fa-book fa-5x"></i>

<?php 
$ret =$dbh->prepare("SELECT id FROM tblissuedbookdetails WHERE StudentID=:sid");
$ret->bindParam(':sid',$sid,PDO::PARAM_STR);
$ret->execute();
$totalissuedbook=$ret->rowCount();
?>

<h3><?php echo $totalissuedbook;?></h3>
Total Issued Books
</div>
</div>
</a>

</div>

</div>
</div>

<?php include('includes/footer.php');?>

<!-- ================= 🤖 CHATBOT (WITH TOGGLE) ================= -->

<!-- Toggle Button -->
<button onclick="toggleChatbot()" 
style="position:fixed;bottom:20px;right:20px;z-index:999;">
💬 Chat
</button>

<!-- Chatbot Box -->
<div id="chatbotBox" 
style="display:none; position:fixed;bottom:70px;right:20px;width:250px;background:#fff;border:1px solid #ccc;padding:10px;z-index:999;">

<h4>Library Bot 🤖</h4>

<div id="chatbox" style="height:150px;overflow:auto;"></div>

<input type="text" id="userMsg" placeholder="Ask something..." style="width:100%;">
<button onclick="sendMsg()">Send</button>

</div>

<script>
function sendMsg(){
    let msg = document.getElementById("userMsg").value;

    if(msg.trim() === "") return;

    fetch("chatbot.php?msg=" + encodeURIComponent(msg))
    .then(res => res.text())
    .then(data => {
        document.getElementById("chatbox").innerHTML += 
        "<p><b>You:</b> " + msg + "</p>" +
        "<p><b>Bot:</b> " + data + "</p>";

        document.getElementById("chatbox").scrollTop = 
        document.getElementById("chatbox").scrollHeight;
    });

    document.getElementById("userMsg").value = "";
}

// Toggle Chatbot
function toggleChatbot(){
    let box = document.getElementById("chatbotBox");

    if(box.style.display === "none" || box.style.display === ""){
        box.style.display = "block";
    } else {
        box.style.display = "none";
    }
}
</script>

<!-- JS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>

</body>
</html>

<?php } ?>