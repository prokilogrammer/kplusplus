<?php
echo '
<p>
<span class="blue"> K++ Event Registration!</span><br><br>
<form action="#" method="post"  id="register">
    <label for="username">Username*: </label><br>
    <input class="field" type="text" name="username" id="username" value="" size="23" /><br><br>
    <label for="password">Password*:</label><br>
    <input class="field" type="password" name="password" id="password" size="23" /><br><br>
    <label for="repassword">Retype Password*:</label><br>
    <input class="field" type="password" name="repassword" id="repassword" size="23" /><br><br>
    <label for="email">Email ID*:</label><br>
    <input class="field" type="text" name="email" id="email" size="30" onBlur="validateMail();"/><span style="font-weight: bold; color: #D33;" id="emailError"></span><br>
    Give your valid mail id, as your account will be validated using an email sent to you.<br><br>
<input type="submit" name="submit" value="Register" onClick="registerMe();return false;" /></form>
</p>				

';


?>