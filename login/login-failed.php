<?php
/*echo "<script type='text/javascript'>alert('Login will be active from 16 Dec 00:00 hrs. Meanwhile read the problem statements,and how-to-play docs and prepare for the game');</script>";
*/
echo '
<script type="text/javascript">
	alert(\'Login Failed. It may be because you have not validated your email or you have entered your username/password wrongly.\');
	
</script>
<div id="top">
		<!-- login -->
		<form action="#" method="post" >
			<ul class="login">
		    	<li class="left">&nbsp;</li>
		        <li>
				<label for="log"><b>Username: </b></label>
				<input class="field" type="text" name="log" id="log" value="" size="23" />
			</li>
			<li>
				<label for="pwd"><b>Password:</b></label>
				<input class="field" type="password" name="pwd" id="pwd" size="23" />
			</li>
			<li>|</li>
			<li>
				<input type="submit" name="submit" value="" class="button_login" onClick="loginMe();return false;" />
			</li>
				<input type="hidden" name="redirect_to" value=""/>
			<li>|</li>
				   <li>
				   <a href="#" id="newregister" onClick="register()">Register</a></li>

			</ul> <!-- / login -->
		</form>
		</div> <!-- / top -->
';
?>
