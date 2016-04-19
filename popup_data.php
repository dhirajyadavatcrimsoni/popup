<?php

error_reporting(1);
ini_set('SMTP', 'mail.crimsoni.com'); 
ini_set('smtp_port', 587); 

    
require_once 'PHPMailer/PHPMailerAutoload.php';
require_once 'PHPMailer/connection_string.php';

echo '<pre>';
print_r($_REQUEST);

echo '</pre>';


exit();
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
mysql_set_charset('utf8',$conn);
mysql_select_db($dbname);

if(isset($_REQUEST['name']) && isset($_REQUEST['email'])){
   // if($_SERVER['HTTP_HOST'] =='edisense.com' && $_SERVER['HTTP_REFERER'] =='http://edisense.com/test/useractivity/enago/23.htm'){
     if($_SERVER['HTTP_HOST'] =='my.enago.com' && $_SERVER['HTTP_REFERER'] =='https://my.enago.com/jp/23.htm' || $_SERVER['HTTP_REFERER'] =='my.enago.com/jp/23.htm'){
     $path_file = "newsletter/discount-campaign.html";
        if(($Content = file_get_contents($path_file)) === false) {
            $Content = " ";
        }else{
            //var_dump(file_get_contents($path_file));
        }
        $Content = mb_convert_encoding($Content, "utf-8","shift_jis");
        $name= $_REQUEST['name'];
        $email = $_REQUEST['email'];
         $to = $email;
        //mb_language("ja");
        $subject = "【英文校正エナゴ】アブストラクト無料キャンペーンのご案内 ";


        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $username;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($from, 'Enago-JP');
        //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress($to);               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        //$mail->setLanguage('ja', '/PHPMailer/language/');
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $Content;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
           // echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            $mail = 'false';
        } else {
            //echo 'Message has been sent';
            $mail = true;
        }
         // var_dump(mail($email, $subject, $Content, $headers));
          $sql = "INSERT INTO iframe (name, email) VALUES('$name', '$email')";

          $res = mysql_query($sql) or die(mysql_error());
          if($mail && $res){
            echo json_encode($mail);
          }else{
            echo 'db: '.$res;
          }

      }else{
        echo 'error-host';
    }
}else{
    echo 'error-request';
}

 ?>
 
