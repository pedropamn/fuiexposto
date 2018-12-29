<?php
require('func.php');
error_reporting(0);

	

$user = $_POST['user'];
$pass = $_POST['pass'];
$txt_broad = $_POST['txt_broad'];
if($user == USER_BROADCAST && $pass == PASS_BROADCAST){
	echo '
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
			<textarea rows=20 cols=150 name="txt_broad"></textarea>
			<input type="hidden" name="user" value="'.$user.'" />
			<input type="hidden" name="pass" value="'.$pass.'" /><br>
			<input type="submit" />
		</form>
	';
	
	
	if(isset($txt_broad)){
		broadcast($txt_broad);
	}
}
else{ ?>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" name="login">
			<input placeholder="Username" type="text" name="user" />
			<input placeholder="Senha" type="password" name="pass" />
			<input type="submit" />
		</form>

<?php } ?>
