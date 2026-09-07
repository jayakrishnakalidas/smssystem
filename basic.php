<?php
session_start();
$logFile = "log.html";

if(isset($_GET['logout'])){
    if(isset($_SESSION['name'])){
        $msg = "<div><b>".$_SESSION['name']."</b> left.</div>\n";

        $old="";
        if(file_exists($logFile)){
            $old=file_get_contents($logFile);
        }

        file_put_contents($logFile,$msg.$old,LOCK_EX);
    }

    session_destroy();
    header("Location: sms.php");
    exit;
}

if(isset($_POST['enter'])){
    if($_POST['name']!=""){

        $_SESSION['name']=htmlspecialchars($_POST['name']);

        $msg="<div><b>".$_SESSION['name']."</b> joined.</div>\n";

        $old="";
        if(file_exists($logFile)){
            $old=file_get_contents($logFile);
        }

        file_put_contents($logFile,$msg.$old,LOCK_EX);
    }
}

function loginForm(){
echo '
<form method="post">
<input type="text" name="name" placeholder="Enter name" required>
<input type="submit" name="enter" value="Enter">
</form>';
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Light Chat</title>

<style>

body{
font-family:Arial;
background:#f2f2f2;
}

#wrapper{
width:95%;
margin:auto;
}

#chatbox{
height:250px;
overflow:auto;
background:#fff;
border:1px solid #ccc;
padding:5px;
}

#menu{
background:#ddd;
padding:5px;
margin-bottom:5px;
}

input{
padding:5px;
}

button{
padding:5px;
}

</style>

</head>

<body>

<?php
if(!isset($_SESSION['name'])){
loginForm();
}
else{
?>

<div id="wrapper">

<div id="menu">
Welcome <b><?php echo $_SESSION['name']; ?></b>
<a href="sms.php?logout=true">Exit</a>
</div>

<div id="chatbox">

<?php
if(file_exists($logFile)){
readfile($logFile);
}
?>

</div>

<br>

<button onclick="reloadChat()">Reload Chat</button>

<br><br>

<form id="messageForm">

<input type="text" id="usermsg" placeholder="Type message">

<input type="submit" value="Send">

</form>

</div>

<script>

/* SEND MESSAGE */

document.getElementById("messageForm").onsubmit=function(e){

e.preventDefault();

var msg=document.getElementById("usermsg").value.trim();

if(msg==="") return;

var xhr=new XMLHttpRequest();

xhr.open("POST","post.php",true);

xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

xhr.send("text="+encodeURIComponent(msg));

document.getElementById("usermsg").value="";

};


/* RELOAD CHAT */

function reloadChat(){

var xhr=new XMLHttpRequest();

xhr.open("GET","log.html?"+Date.now(),true);

xhr.onload=function(){

if(this.status===200){

document.getElementById("chatbox").innerHTML=this.responseText;

}

};

xhr.send();

}

</script>

<?php } ?>

</body>
</html>