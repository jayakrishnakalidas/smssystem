<?php
session_start();

if(isset($_POST['text']) && isset($_SESSION['name'])){

$logFile = "log.html";

$text = htmlspecialchars(trim($_POST['text']));

if($text != ""){

$msg = "<div><b>".$_SESSION['name'].":</b> ".$text."</div>\n";

/* get old messages */
$old = "";
if(file_exists($logFile)){
$old = file_get_contents($logFile);
}

/* put new message on top */
file_put_contents($logFile, $msg.$old, LOCK_EX);

}

}
?>
?>