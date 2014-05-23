<?php
/*This php function will calculate the points at the time of invocation
 */
date_default_timezone_set('Asia/Calcutta');

function calcPoints()
{

$startOfEvent = mktime(01,00,00,01,02,2009); /* hh:mm:ss mm/dd/yyyy */
/*********** DEBUG *********
echo (time() - $startOfEvent) . "<br>" . time();
echo "<br>Point Reduction " . (int)( ( (time() - $startOfEvent) - 120 ) / 7200) ; //This 120 seconds is the two min grace time
***************************/

$result =  300 - (int)( ( (time() - $startOfEvent) - 120 ) / 7200);
return $result;
}

function base64_url_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_$');
}

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='));
}


?>
