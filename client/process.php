<?php
if($_POST['email']){

$mysqli = new mysqli('localhost','smartchat','smartchat','smartchat');

if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$results = $mysqli->query("SELECT mail_offline FROM auto_answering");
	
$row = $results->fetch_assoc(); 

$to = $row["mail_offline"];
			
$subject = 'Website Change Reqest';

$headers = "From: " . strip_tags($_POST['email']) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8";

$message = strip_tags($_POST['comment']);
$subject = strip_tags($_POST['first_name']).strip_tags($_POST['first_name']);
$message = strip_tags($_POST['comment']);
	
if (mail($to, $subject, $message, $headers)) {
  echo 'შეტყობინება გაგზავნილია';
} 
else {
  echo 'შეტყიობინების გაგზავნა ვერ ხერხდება';
}
  

$results->free();


$mysqli->close();


	
}

?>
