<?php

session_start();

ob_start("ob_gzhandler"); // Enable gzip compression (important for 2G)

$logFile = "log.html";

if(isset($_GET['logout'])){

    if(isset($_SESSION['name'])){

        $msg = "<div class='msgln'><span class='left-info'><b>"

        .$_SESSION['name']."</b> left.</span></div>";

        file_put_contents($logFile, $msg, FILE_APPEND | LOCK_EX);

    }

    session_destroy();

    header("Location: sms.php");

    exit;

}

if(isset($_POST['enter']) && !empty($_POST['name'])){

    $_SESSION['name'] = htmlspecialchars(trim($_POST['name']));

    $msg = "<div class='msgln'><span class='left-info'><b>"

    .$_SESSION['name']."</b> joined.</span></div>";

    file_put_contents($logFile, $msg, FILE_APPEND | LOCK_EX);

}

function loginForm(){

echo '<div id="loginform">

<form method="post">

<input type="text" name="name" placeholder="Enter name" required>

<input type="submit" name="enter" value="Enter">

</form></div>';

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Light Chat</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php

if(!isset($_SESSION['name'])){

    loginForm();

} else {

?>

<div id="wrapper">

<div id="menu">

Welcome <b><?=$_SESSION['name']?></b>

<a href="sms.php?logout=true">Exit</a>

</div>

<div id="chatbox">

<?php

if(file_exists($logFile)){

    readfile($logFile);

}

?>

</div>

<form id="messageForm">

<input type="text" id="usermsg" autocomplete="off" placeholder="Type message">

<input type="submit" value="Send">

</form>

</div>

<script>

var lastLength = 0;

document.getElementById("messageForm").onsubmit = function(e){

    e.preventDefault();

    var msg = document.getElementById("usermsg").value.trim();

    if(msg === "") return;

    var xhr = new XMLHttpRequest();

    xhr.open("POST","post.php",true);

    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.send("text="+encodeURIComponent(msg));

    document.getElementById("usermsg").value="";

};

function loadLog(){

    var xhr = new XMLHttpRequest();

    xhr.open("GET","log.html",true);

    xhr.onload = function(){

        if(this.responseText.length !== lastLength){

            document.getElementById("chatbox").innerHTML = this.responseText;

            document.getElementById("chatbox").scrollTop =

            document.getElementById("chatbox").scrollHeight;

            lastLength = this.responseText.length;

        }

    };

    xhr.send();

}

setInterval(loadLog, 5000); // Increased to 5 seconds (better for 2G)

</script>

<?php } ?>

</body>

</html>