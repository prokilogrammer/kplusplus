<?php
require_once "/home/kurukshe/php/Mail.php";
function sendMail($to,$subject,$body)
{


$from = "Kplusplus Admin <admin@kplusplus.kurukshetra.org.in>";

$host = "localhost";
$username = "admin+kplusplus.kurukshetra.org.in";
$password = "hA4HaU%FeO";

$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => true,
    'username' => $username,
    'password' => $password));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) 
{
  return $mail->getMessage();
} 
else 
{
  //echo("<p>Message successfully sent!</p>");
  return true;
}

}
?>
