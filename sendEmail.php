<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  'phpmailer/src/Exception.php';
require  'phpmailer/src/PHPMailer.php';
require  'phpmailer/src/SMTP.php';
function sendEmail(){
	if( !empty($_POST['email']) &&
		!empty($_POST['subject']) &&
		!empty($_POST['message'])){
			
		// if(isset($_POST["send"])){
			$mail = new PHPMailer(true);
			
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'simfyp22s404@gmail.com'; //gmail name
			$mail->Password = 'xxeoahodgafooygh'; //gmail app password
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
			
			$mail->setFrom('simfyp22s404@gmail.com');
			
			$mail->addAddress($_POST["email"]);
			
			$mail->isHTML(true);
			
			$mail->Subject = $_POST["subject"];
			$mail->Body = $_POST["message"];
			
			$mail->send();
			
			echo "success";
		
		
	} else echo "All fields are required";
}
?>