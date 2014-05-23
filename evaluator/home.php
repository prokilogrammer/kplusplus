<?php require('validate.php'); ?>
<html>
<body>
Plz clear your cache before proceeding..
<form action="login-exec.php" method="post">
			<ul class="login">
		    	<li class="left">&nbsp;</li>
		        <li>
				<label for="log"><b>Username: </b></label>
				<input  type="text" name="log"   size="23" />
			</li>
			<li>
				<label for="pwd"><b>Password:</b></label>
				<input  type="password" name="pwd"  size="23" />
			</li>
			
			<li>
				<input type="submit" name="submit" value="login"  />
			</li>
				<input type="hidden" name="redirect_to" value=""/>

			</ul> <!-- / login -->
		</form>
</body>
</html>
