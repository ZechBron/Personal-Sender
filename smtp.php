<?php

include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:/users/acer/vendor/autoload.php';


$readEmails = file_get_contents("emailist.txt");
$email = preg_split("/[\s,]+/", $readEmails);
$count = count($email);

for ($i = 0; $i < $count; $i++) {
//$email[$i];

/* kayo na bahala kung tig 500 muna bawat send ewan ko bahala kayo 
purpose nito lagay mong lists mo ay 5k tas pahinga bawat 500 na nasend */
if ($i%500 == 0) {
echo "Please wait... nagpapahinga muna\n";
sleep(60);
}

if (stripos($email[$i], "yahoo") !== false) {
$SendName = '=?UTF-8?B?' . base64_encode($SenderName) . '=';
$subject = '=?UTF-8?B?' . base64_encode($Subject) . '=';
}
else {
$SendName = $SenderName;
$subject = $Subject;
}

/* ---[ Change String ]---

beta version pa lang ito lalagyan ko pa ito ng iba. kaso inaatake ako ng katamaran eh
*/
$letter = file_get_contents("letter.txt");
if (stripos($letter, "##email##") !== false) {
$letter = str_replace("##email##", $email[$i], $letter);
}
if (stripos($letter, "##date##") !== false) {
$letter = str_replace("##date##", date("m/d/Y h:i:s a", time()), $letter);
}

/* ---[ Start ]--- */
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->setFrom($username, $SendName);
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->Host = $host;
    $mail->Port = $port;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->XMailer = ' ';
    $mail->CharSet = 'UTF-8';
    $mail->MessageID = '<' . md5(rand()) . '@us-east-4.console.aws.amazon.com>';
    $mail->Encoding = 'base64';
//   $mail->addCustomHeader('kayo na bahala kung ano pa idadagdag nyo', 'anong header gusto nyo');

   $mail->addAddress($email[$i]);
   $mail->isHTML(true);
   $mail->Subject = $subject;


if (stripos($letter, "cid:image") !== false) {

$embed = md5(rand());
$mail->addEmbeddedImage('.jpg', $embed);
$letter = str_replace("cid:image", "cid:" . $embed, $letter);
//$mail->Body = '<a rel="nofollow noopener noreferrer" href="#"><img src="cid:image"></a>';
}

   $mail->Body = $letter;
   $mail->Send();
   echo "[ " . $i . " / " . $count . " ] " . $email[$i] . " => Email sent!" , PHP_EOL;
}
catch (phpmailerException $e) {
    echo "[ " . $i . " / " . $count . " ] " . $email[$i] . " => Error Sending {$e->errorMessage()} \n", PHP_EOL;
}
catch (Exception $e) {
    echo "[ " . $i . " / " . $count . " ] " . $email[$i] . " => Email Not Sent. {$mail->ErrorInfo} \n", PHP_EOL; 
}
}
?>

