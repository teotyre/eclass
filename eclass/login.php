<?php 
@session_start();
?>
<!-- Πλαίσιο σύνδεσης -->
<div id="LoginForm">
	<form id="frmLogin" action="index.php?task=login" method="post" name="frmLogin" >
		<!-- Ο χρήστης συμπληρώνει το username και το password του -->
		<label>Χρήστης:</label><br /> 
		<input class="form-control" type="textbox" name="txtUserName" placeholder="Εισάγετε όνομα"  maxlength="50" autocomplete="on" required autofocus />
	
		<label>Κωδικός:</label><br /> 
		<input autocomplete="off" class="form-control" type="password" name="pwdPassword" placeholder="Εισάγετε κωδικό"  maxlength="50" required autofocus />
		<br /> 
        
		<?php
			$salt = 'SomeSalt';
			$token = sha1(mt_rand(1,1000000) . $salt);
			$_SESSION['token'] = $token;
		?>

		<!-- Πλήκτρο που πατάει ο χρήστης μόλις συμπληρώσει τα πεδία ''όνομα χρήστη'' και ''κωδικός πρόσβασης'' για να συνδεθεί στο λογαριασμό του-->
		<input type='hidden' name='token' value='<?php echo $token; ?>'/>
        <center>
			<input style="font-family:Garamond;  font-size:17px;" class="btn" name="btnEisodos" type="submit" value="Είσοδος"/>
		</center> 
        <hr>
    </form>
</div>