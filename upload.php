<?php

session_start();

$target_dir = "audio/";
$target_file = $target_dir . basename($_FILES["audio"]["name"]);
$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($file_type != "mp3" && $file_type != "wav" && $file_type != "aac" && $file_type != "ogg") {
    echo "Only MP3, WAV, and OGG audio files are allowed.";
} else {
    if (move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file)) {
        echo "The audio file ". basename( $_FILES["audio"]["name"]). " has been uploaded.";

        $audio_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>".$_SESSION['name']."</b> has uploaded an audio file:<br> <a href='".$target_file."'>".$_FILES["audio"]["name"]."</a><br></div>";
        file_put_contents("log.html", $audio_message, FILE_APPEND | LOCK_EX);
    } else {
        echo "Sorry, there was an error uploading your audio file.";
    }
}

?>
