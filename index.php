<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

//config
	//server
	$db_server			= 'localhost';				// Servidor de la base de datos
 	$db_name 			= 'database';				// Nombre de la base de datos
	$db_user 		    = 'User_DB';				// Usuario de la base de datos
	$db_pass 		    = '******************';				// Password de la base de datos
	$file_path			= '/home/nginx/domains/domain/backup'; // Path donde se guardara el archivo de backup

	//email
 	$mail_host          = 'smtp.gmail.com';	 // Host del email SMTP
 	$mail_username		= 'correo@gmail.com';// Usuario de Email SMTP	   
 	$mail_pass			= '*********';   // Password de emai SMTP
	$mail_send_to 		= 'correo@gmail.com';  // Email al que se le enviara el correo
	$mail_from 		    = 'correo@gmail.com';  // Email de From, en caso de que quieran otro.	

//Backup
	$today = new DateTime();
	$today = $today->format('d-m-Y');
	$yesterday = new DateTime('yesterday');
	$yesterday = $yesterday->format('d-m-Y');
	exec('mysqldump -u '.$db_user.' -p'.$db_pass.' '.$db_name.' | gzip > '.$file_path.''.$db_name.'_'.$today.'.sql.gz');
	exec('rm -rf '.$file_path.''.$db_name.'_'.$yesterday.'.sql.gz');



//Email
$mail = new PHPMailer;

$mail->SMTPDebug = 1;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = gethostbyname($mail_host);  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = $mail_username;                 // SMTP username
$mail->Password = $mail_pass;                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom($mail_from, 'Respaldo DB');
$mail->addAddress($mail_send_to);     // Add a recipient

$mail->addAttachment($file_path.$db_name.'_'.$today.'.sql.gz');         // Add attachments

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Respaldo'.$today;
$mail->Body    = 'Este es un mail automatizado de respaldo de la base de datos';
?>



<?php 
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

?>
